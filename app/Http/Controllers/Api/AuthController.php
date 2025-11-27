<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\secondary_student_details;
use App\Models\SecondaryBranch;
use App\Models\SecondaryGrade;
use App\Models\SecondarySpecialization;
use App\Models\SecondarySubBranch;
use App\Models\SecondaryTrack;
use App\Models\StageGrade;
use App\Models\Students;
use App\Models\StudentsLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ✅ Register new student
    public function register(Request $request)
    {


        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'            => 'required|email|unique:students,email',
            'password'         => ['required', Password::min(6)],
            'phone'            => 'nullable|string|max:20',
            'parent_phone'     => 'nullable|string|max:20',
            'governorate_id'   => 'required|exists:governors,id',
            'city_id'          => 'required|exists:cities,id',
            'address'          => 'nullable|string|max:255',
            'date_of_birth'    => 'nullable|date',
            'type_of_study'    => 'nullable|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'mobile_name'      => 'nullable|string|max:255',
        ]);

        $student = Students::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'phone'          => $validated['phone'] ?? null,
            'parent_phone'   => $validated['parent_phone'] ?? null,
            'governorate_id' => $validated['governorate_id'],
            'city_id'        => $validated['city_id'],
            'address'        => $validated['address'] ?? null,
            'date_of_birth'  => $validated['date_of_birth'] ?? null,
            'type_of_study'  => $validated['type_of_study'] ?? null,
            'gender'         => $validated['gender'] ?? null,
            'education'      => $validated['education'] ?? null,
        ]);
        if ($student) {
            $token = $student->createToken('auth_token')->plainTextToken;


            $studentLog = StudentsLogs::query()->create([
                'student_id' => $student->id,
                'mobile_name' => $request->mobile_name,
                'action' => 'register First Login'
            ]);
            if ($studentLog) {
                return response()->json([
                    'message' => 'Registration successful',
                    'token'   => $token,
                    'student' => $student,
                ], 201);
            }
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Students::where('email', $request->email)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        } else {
            $get_student_log = StudentsLogs::query()
                ->where('student_id', $student->id)
                ->where('is_active', true)
                ->latest()->first();
            if ($get_student_log) {
                // User is already logged in from another device
                return response()->json([
                    'message' => 'You are already logged in from another device'
                ], 409);
            }
            // Log the login attempt
            StudentsLogs::query()->create([
                'student_id' => $student->id,
                'mobile_name' => $request->mobile_name ?? 'Unknown',
                'action' => 'Login'
            ]);
        }
        $token = $student->createToken('student_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'student' => $student
        ]);
    }


    // ✅ Get logged-in student info
    public function profile(Request $request)
    {
        $student = $request->user()->load('education');

        if ($student->education && isset($student->education->secondary_track_id)) {
            $student->education->load([
                'secondaryTrack',
                'secondaryGrade',
                'secondaryBranch',
                'secondarySubBranch',
                'secondarySpecialization',
            ]);

            // 🧹 نخفي الـ IDs دي من الـ response
            unset(
                $student->education->secondary_track_id,
                $student->education->secondary_grade_id,
                $student->education->secondary_branch_id,
                $student->education->secondary_sub_branch_id,
                $student->education->secondary_specialization_id
            );
        }
        return response()->json([
            'status'  => true,
            'student' => $student,
        ]);
    }

    // ✅ Logout student (revoke token)
    public function logout(Request $request)
    {

        $user = $request->user();
        $studentLog = StudentsLogs::query()->where('student_id', $user->id)
            ->where('is_active', true)
            ->latest()->first();

        if ($studentLog && $studentLog->exists()) {
            $studentLog->update(['action' => 'logout', 'is_active' => false]);
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully',
            // 'student_log' => $studentLog
        ]);
    }

    // ✅ Store student
    public function store(Request $request)
    {
        $educationMap = config('education_phases');
        $educationTypeKey = $request->education_type_key;

        // 1. التحقق الأساسي من نوع المرحلة
        if (!isset($educationMap[$educationTypeKey])) {
            return response()->json(['message' => 'Invalid education phase key.'], 422);
        }

        $morphClass = $educationMap[$educationTypeKey];
        $morphId = null;

        DB::beginTransaction();
        try {

            // 2. معالجة بيانات المرحلة التعليمية
            if ($educationTypeKey === 'secondary') {
                // *** حالة الثانوية (النظام الجديد) ***

                // أ. التحقق من مفاتيح الثوابت الثانوية
                $request->validate([
                    'secondary_track_key' => ['required', 'exists:secondary_tracks,track_id'],
                    'secondary_grade_key' => ['required', 'exists:secondary_grades,grade_id'],
                    'secondary_branch_key' => ['nullable', 'exists:secondary_branches,branch_id'],
                    'secondary_sub_branch_key' => [
                        'nullable',
                        Rule::exists('secondary_sub_branches', 'sub_branch_id')
                    ],
                    'secondary_specialization_key' => ['nullable', 'exists:secondary_specializations,spec_id'],
                ]);

                // ب. استرجاع الـ IDs
                $trackId = SecondaryTrack::where('track_id', $request->secondary_track_key)->value('id');
                $gradeId = SecondaryGrade::where('grade_id', $request->secondary_grade_key)->value('id');
                $branchId = $request->secondary_branch_key ? SecondaryBranch::where('branch_id', $request->secondary_branch_key)->value('id') : null;
                $specId = $request->secondary_specialization_key ? SecondarySpecialization::where('spec_id', $request->secondary_specialization_key)->value('id') : null;
                $subBranchId = $request->secondary_sub_branch_key
                    ? SecondarySubBranch::where('sub_branch_id', $request->secondary_sub_branch_key)->value('id')
                    : null;
                // ج. إنشاء السجل الوسيط (الـ Morph Target)
                $secondaryDetail = secondary_student_details::create([
                    'secondary_track_id' => $trackId,
                    'secondary_grade_id' => $gradeId,
                    'secondary_branch_id' => $branchId,
                    'secondary_sub_branch_id' => $subBranchId, // ✅ أضفناه هنا
                    'secondary_specialization_id' => $specId,
                ]);
                $morphId = $secondaryDetail->id;
            } else {
                // *** حالة المراحل الأخرى (Primary, Prep) ***

                // سنفترض أن هذه المراحل تأتي بـ ID موجود مسبقاً في الطلب
                // $request->validate([
                //     'education_id' => ['required', 'string', Rule::exists($educationMap[$educationTypeKey], 'grade_id')],
                // ]);
                // $morphId = $request->education_id;

                // First validate the request
                $validated = $request->validate([
                    'education_id' => ['required', 'string', Rule::exists('stage_grades', 'grade_id')],
                ]);

                // Then get the full record using the grade_id
                $grade = StageGrade::where('grade_id', $request->education_id)->first();

                // Now you can access the id
                $morphId = $grade->id;
            }

            // 3. التحقق من صحة بيانات الطالب الأخرى (يجب تعديلها لتناسب حقولك)
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:students,email',
                'password' => 'required|string|min:6',
                'governorate_id' => 'required|exists:governors,id',
                'city_id' => 'required|exists:cities,id',

            ]);

            // 4. جملة الحفظ الموحدة (The Unified Save Statement)
            $student = Students::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'governorate_id' => $request->governorate_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'phone' => $request->phone ?? null,
                'parent_phone' => $request->parent_phone ?? null,
                'date_of_birth' => $request->date_of_birth ?? null,
                'type_of_study' => $request->type_of_study ?? null,
                'gender' => $request->gender ?? null,
                // حقول العلاقة المتعددة الأشكال (Morph Keys)
                'education_id'   => $morphId,
                'education_type' => $morphClass, // اسم الكلاس الكامل
            ]);

            $studentLog = StudentsLogs::query()->create([
                'student_id' => $student->id,
                'mobile_name' => $request->mobile_name,
                'action' => 'register First Login'
            ]);

            $token = null;
            if ($studentLog) {
                $token = $student->createToken('auth_token')->plainTextToken;
            }
            DB::commit();

            return response()->json([
                'message' => 'Student created successfully',
                'token' => $token,
                'student' => $student,
                'Student_log' => $studentLog
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred during student registration.',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Request $request)
    {
        return response()->json([
            'status'  => true,
            'student' => $request->user(),
        ]);
    }
}
