<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\code_list_body;
use App\Models\enrolling_students;
use App\Models\Students;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class StudentRedeemController extends Controller
{
    protected function redeemFailureResponse(Request $request, string $message, int $status = 400)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('website.home')->with('error', $message);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    /**
     * Show the redeem code form or the unauthenticated page.
     */
    public function showRedeemForm(Request $request)
    {
        $appScheme = config('app.url_scheme', env('MINDLY_APP_URL_SCHEME', 'mindly'));
        $welcomeDeepLink = "{$appScheme}://welcome";

        // 1. Check for token in query parameter (automatic login from app)
        if ($request->has('token')) {
            $plainToken = $request->query('token');
            $tokenInstance = PersonalAccessToken::findToken($plainToken);

            if ($tokenInstance && $tokenInstance->tokenable instanceof Students) {
                // Log the student in using the student_web session guard
                Auth::guard('student_web')->login($tokenInstance->tokenable);

                // Redirect to the clean page to remove the token from the URL bar
                return redirect()->route('student.redeem.show');
            }

            // Invalid token means this deeplink should fall back to the app welcome screen.
            return redirect()->away($welcomeDeepLink);
        }

        $student = Auth::guard('student_web')->user();

        // If the user is not already authenticated, send the deeplink to the app welcome screen.
        if (! $student) {
            return redirect()->away($welcomeDeepLink);
        }

        // 2. Fetch platform main settings data required by the main layout footer
        $settings = new class () {
            use \App\Helpers\GetMainData;
        };
        $mainData = $settings->getMainData();

        // 3. Render page according to auth status
        return view('website.redeem', [
            'mainData' => $mainData,
            'student' => $student,
            'appScheme' => $appScheme,
        ]);
    }

    /**
     * Process the code redemption.
     */
    public function redeem(Request $request)
    {
        // Must be authenticated via student_web
        if (!Auth::guard('student_web')->check()) {
            return $this->redeemFailureResponse(
                $request,
                __('You must be logged in to redeem a code.'),
                401
            );
        }

        $request->validate([
            'cardCode' => 'required|string',
        ]);

        $student = Auth::guard('student_web')->user();
        $user_id = $student->id;

        // Check code
        $check_code = code_list_body::query()
            ->where('code', $request->cardCode)
            ->where('is_used', 0)
            ->with(['tos', 'codeListHead.teacherCourseOverview'])
            ->first();

        if (!$check_code) {
            return $this->redeemFailureResponse(
                $request,
                __('This code is invalid or has already been used! 🚫')
            );
        }

        // Check if the student is already enrolled in this specific course overview to prevent duplicate enrollment
        $courseOverview = $check_code->codeListHead->teacherCourseOverview ?? null;
        if ($courseOverview) {
            $alreadyEnrolled = enrolling_students::query()
                ->where('student_id', $user_id)
                ->where('is_completed', false)
                ->whereHas('code.codeListHead', function ($query) use ($courseOverview) {
                    $query->where('teacher_course_overviews_id', $courseOverview->id);
                })
                ->exists();

            if ($alreadyEnrolled) {
                return $this->redeemFailureResponse(
                    $request,
                    __('You are already enrolled in this course! 📚')
                );
            }
        }

        $get_duration = $check_code->tos->duration ?? 1; // Default to 1 month if not specified
        $add_duration = Carbon::now()->addMonths($get_duration)->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');

        $create_enrolling = enrolling_students::query()->create([
            'student_id' => $user_id,
            'code_list_body_id' => $check_code->id,
            'enrolled_at' => $now,
            'completed_at' => $add_duration,
            'is_completed' => false,
        ]);

        if ($create_enrolling) {
            $check_code->update([
                'is_used' => true,
                'used_by' => $user_id,
                'used_at' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        // Get localized course name
        $courseName = $courseOverview ? $courseOverview->getTranslation('name', App::getLocale()) : __('Course');
        $courseId = $courseOverview ? $courseOverview->id : 0;
        $appScheme = config('app.url_scheme', env('MINDLY_APP_URL_SCHEME', 'mindly'));
        $deepLink = "{$appScheme}://course/{$courseId}";

        if (! $request->expectsJson()) {
            return redirect()->away($deepLink);
        }

        return response()->json([
            'success' => true,
            'message' => __('Course enrolled successfully ✔️👌'),
            'course_name' => $courseName,
            'course_id' => $courseId,
            'deep_link' => $deepLink,
        ], 200);
    }
}
