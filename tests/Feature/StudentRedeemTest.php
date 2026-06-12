<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\governor;
use App\Models\Students;
use App\Models\code_list_body;
use App\Models\code_list_head;
use App\Models\type_of_subscriptions;
use App\Models\TeacherCourseOverview;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StudentRedeemTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $country;
    protected $governor;
    protected $city;
    protected $tos;
    protected $teacher;
    protected $course;
    protected $codeHead;
    protected $codeBody;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create location tables
        $this->country = Country::create([
            'name' => ['en' => 'Egypt', 'ar' => 'مصر'],
            'key' => 'EG',
            'code' => '20',
        ]);

        $this->governor = governor::create([
            'country_id' => $this->country->id,
            'name' => 'Cairo',
            'status' => true,
        ]);

        $this->city = City::create([
            'name' => 'Nasr City',
            'governors_id' => $this->governor->id,
            'status' => true,
        ]);

        // 2. Create student
        $this->student = Students::create([
            'name' => ['en' => 'Test Student', 'ar' => 'طالب تجريبي'],
            'email' => 'student@test.com',
            'password' => bcrypt('password123'),
            'governorate_id' => $this->governor->id,
            'city_id' => $this->city->id,
            'status' => true,
        ]);

        // 3. Create Subscription Type
        $this->tos = type_of_subscriptions::create([
            'duration' => 3, // 3 months
            'price' => 150.00,
            'name' => ['en' => '3 Months', 'ar' => '٣ شهور'],
        ]);

        // 4. Create Teacher & Course Overview
        $this->teacher = Teacher::create([
            'name' => ['en' => 'John Doe', 'ar' => 'جون دو'],
            'email' => 'teacher@test.com',
            'password' => bcrypt('password123'),
            'status' => true,
            'city_id' => $this->city->id,
        ]);

        $this->course = TeacherCourseOverview::create([
            'name' => ['en' => 'English Grammar 101', 'ar' => 'قواعد الإنجليزية 101'],
            'teacher_id' => $this->teacher->id,
            'price' => 100,
            'state' => true,
        ]);

        // 5. Create Code List Head & Body
        $this->codeHead = code_list_head::create([
            'teacher_course_overviews_id' => $this->course->id,
            'type_of_subscription_id' => $this->tos->id,
            'quantity' => 10,
        ]);

        $this->codeBody = code_list_body::create([
            'code_list_head_id' => $this->codeHead->id,
            'type_of_subscription_id' => $this->tos->id,
            'is_used' => false,
        ]);
    }

    /**
     * Test direct unauthenticated access redirect/instructions.
     */
    public function test_unauthenticated_user_sees_open_app_deep_link(): void
    {
        $response = $this->get(route('student.redeem.show'));

        $response->assertStatus(200);
        $response->assertSee('Authentication Required');
        $response->assertSee('mindly://welcome');
    }

    /**
     * Test auto-login with valid Sanctum token query parameter.
     */
    public function test_valid_token_query_authenticates_student_and_redirects(): void
    {
        $token = $this->student->createToken('test_token')->plainTextToken;

        // Make requests with token
        $response = $this->get(route('student.redeem.show', ['token' => $token]));

        // Should redirect to clean URL
        $response->assertStatus(302);
        $response->assertRedirect(route('student.redeem.show'));

        // Follow redirect and assert authenticated view is rendered
        $followResponse = $this->followingRedirects()->get(route('student.redeem.show', ['token' => $token]));
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Welcome back');
        $followResponse->assertSee('student@test.com');
        
        $this->assertTrue(Auth::guard('student_web')->check());
        $this->assertEquals($this->student->id, Auth::guard('student_web')->id());
    }

    /**
     * Test auto-login fails with invalid Sanctum token.
     */
    public function test_invalid_token_query_does_not_authenticate(): void
    {
        $response = $this->get(route('student.redeem.show', ['token' => 'invalid_token_format']));

        $response->assertStatus(200);
        $response->assertSee('Authentication Required');
        $this->assertFalse(Auth::guard('student_web')->check());
    }

    /**
     * Test successful code redemption.
     */
    public function test_authenticated_student_can_redeem_valid_code(): void
    {
        // Login student session
        Auth::guard('student_web')->login($this->student);

        $response = $this->postJson(route('student.redeem.submit'), [
            'cardCode' => $this->codeBody->code,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'course_name' => 'English Grammar 101',
        ]);

        // Verify database updates
        $this->codeBody->refresh();
        $this->assertTrue((bool)$this->codeBody->is_used);
        $this->assertEquals($this->student->id, $this->codeBody->used_by);
        $this->assertDatabaseHas('enrolling_students', [
            'student_id' => $this->student->id,
            'code_list_body_id' => $this->codeBody->id,
            'is_completed' => false,
        ]);
    }

    /**
     * Test cannot redeem an already used code.
     */
    public function test_student_cannot_redeem_already_used_code(): void
    {
        // Mark code as used
        $this->codeBody->update([
            'is_used' => true,
            'used_by' => 999,
        ]);

        Auth::guard('student_web')->login($this->student);

        $response = $this->postJson(route('student.redeem.submit'), [
            'cardCode' => $this->codeBody->code,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test cannot redeem code when unauthenticated.
     */
    public function test_unauthenticated_user_cannot_redeem_code(): void
    {
        $response = $this->postJson(route('student.redeem.submit'), [
            'cardCode' => $this->codeBody->code,
        ]);

        $response->assertStatus(401);
    }
}
