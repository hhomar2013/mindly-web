<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SecondaryBranch;
use App\Models\SecondaryGrade;
use App\Models\SecondarySpecialization;
use App\Models\SecondarySubBranch;
use App\Models\SecondaryTrack;
use App\Models\secondary_student_details;
use App\Models\StageGrade;
use App\Models\Students;
use App\Models\StudentsLogs;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function login(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $student = Students::where('email', $request->email)->first();
        if (! $student || ! Hash::check($request->password, $student->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        } else {
            $check = $otpService->AnotherDevice($student->id);
            if ($check) {
                return response()->json([
                    'message' => 'You are already logged in from another device',
                ], 409);
            }
        }

        $otp = $this->sendOtp($request);
        if ($otp) {
            return response()->json([
                'status'  => true,
                'message' => 'OTP has been sent to your email. Please check your inbox.',
            ]);
        }
    }

    public function loginSendOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email'       => 'required|email',
            'otp'         => 'required',
            'mobile_name' => 'required',
        ]);

        $isOtpValid = $otpService->verifyOtp($request->email, $request->otp);
        if (! $isOtpValid) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP',
            ], 422);
        }
        $student = Students::query()->where('email', $request->email)->first();
        $student->update(['status' => true]);
        // Log the login attempt
        $save = StudentsLogs::query()->create([
            'student_id'  => $student->id,
            'mobile_name' => $request->mobile_name,
            'action'      => 'Login',
        ]);
        if ($save) {
            $token = $student->createToken('student_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'token'   => $token,
                'student' => $student,
            ]);
        }
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
        $user    = $request->user();
        $id      = $user->id;
        $student = students::find($id);
        $student->update(['status' => false]);
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
        ]);
    }
    // ✅ First Send OTP
    public function sendOtp(Request $request)
    {
        $otpService = new OtpService();
        $otp        = $otpService->sendOtp($request->email);
        return $otp ? true : false;
    }

    public function sendAnotherOneOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $otp = $otpService->sendOtp($request->email);
        if ($otp) {
            return response()->json([
                'status'  => true,
                'message' => 'A new OTP has been sent to your email. Please check your inbox.',
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to resend OTP. Please try again later.',
            ], 500);
        }
    } // sendAnotherOneOtp
      // ✅ Store student Register
    public function store(Request $request, OtpService $otpService)
    {
        // Validate basic student data
        $request->validate([
            'name'           => 'required',
            'email'          => 'required|email|unique:students,email',
            'password'       => 'required|min:6',
            'governorate_id' => 'required|exists:governors,id',
            'city_id'        => 'required|exists:cities,id',
        ]);

        DB::beginTransaction();
        try {

            // 2️⃣ معالجة المرحلة التعليمية بالكامل نفس كودك
            $educationMap     = config('education_phases');
            $educationTypeKey = $request->education_type_key;

            if (! isset($educationMap[$educationTypeKey])) {
                return response()->json(['message' => 'Invalid education phase key.'], 422);
            }

            $morphClass = $educationMap[$educationTypeKey];
            $morphId    = null;

            // Secondary education branch
            if ($educationTypeKey === 'secondary') {

                $request->validate([
                    'secondary_track_key'          => ['required', 'exists:secondary_tracks,track_id'],
                    'secondary_grade_key'          => ['required', 'exists:secondary_grades,grade_id'],
                    'secondary_branch_key'         => ['nullable', 'exists:secondary_branches,branch_id'],
                    'secondary_sub_branch_key'     => ['nullable', Rule::exists('secondary_sub_branches', 'sub_branch_id')],
                    'secondary_specialization_key' => ['nullable', 'exists:secondary_specializations,spec_id'],
                ]);

                $trackId     = SecondaryTrack::where('track_id', $request->secondary_track_key)->value('id');
                $gradeId     = SecondaryGrade::where('grade_id', $request->secondary_grade_key)->value('id');
                $branchId    = $request->secondary_branch_key ? SecondaryBranch::where('branch_id', $request->secondary_branch_key)->value('id') : null;
                $specId      = $request->secondary_specialization_key ? SecondarySpecialization::where('spec_id', $request->secondary_specialization_key)->value('id') : null;
                $subBranchId = $request->secondary_sub_branch_key
                    ? SecondarySubBranch::where('sub_branch_id', $request->secondary_sub_branch_key)->value('id')
                    : null;

                $secondaryDetail = secondary_student_details::create([
                    'secondary_track_id'          => $trackId,
                    'secondary_grade_id'          => $gradeId,
                    'secondary_branch_id'         => $branchId,
                    'secondary_sub_branch_id'     => $subBranchId,
                    'secondary_specialization_id' => $specId,
                ]);

                $morphId = $secondaryDetail->id;
            } else {
                $validated = $request->validate([
                    'education_id' => ['required', 'string', Rule::exists('stage_grades', 'grade_id')],
                ]);

                $grade   = StageGrade::where('grade_id', $request->education_id)->first();
                $morphId = $grade->id;
            }

            // 3️⃣ Create the student
            $student = Students::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => bcrypt($request->password),
                'governorate_id' => $request->governorate_id,
                'city_id'        => $request->city_id,
                'address'        => $request->address,
                'phone'          => $request->phone ?? null,
                'parent_phone'   => $request->parent_phone ?? null,
                'date_of_birth'  => $request->date_of_birth ?? null,
                'type_of_study'  => $request->type_of_study ?? null,
                'gender'         => $request->gender ?? null,
                'education_id'   => $morphId,
                'education_type' => $morphClass,
            ]);

            $otp = $this->sendOtp($request);
            if ($otp) {
                DB::commit();
            }

            return response()->json([
                'status'  => true,
                'message' => 'OTP has been sent to your email. Please check your inbox to confirm your registration.',
                // 'token'       => $token,
                // 'student' => $student,
                // 'Student_log' => $studentLog,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage(),
            ], 422);
        }
    }

    public function confirmRegister(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email'       => 'required|email',
            'otp'         => 'required',
            'mobile_name' => 'required',
        ]);

        $isOtpValid = $otpService->verifyOtp($request->email, $request->otp);
        if (! $isOtpValid) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP',
            ], 422);
        }
        $student = Students::query()->where('email', $request->email)->first();

        // Log the login attempt
        $save = StudentsLogs::query()->create([
            'student_id'  => $student->id,
            'mobile_name' => $request->mobile_name,
            'action'      => 'First Register Login',
        ]);
        if ($save) {
            $token = $student->createToken('student_token')->plainTextToken;
            return response()->json([
                'message' => 'Registration confirmed and login successful',
                'token'   => $token,
                'student' => $student,
            ]);
        }
    }

    public function show(Request $request)
    {
        return response()->json([
            'status'  => true,
            'student' => $request->user(),
        ]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $student = $request->user();
        if ($student->image && Storage::disk('public')->exists($student->getRawOriginal('image'))) {
            Storage::disk('public')->delete($student->getRawOriginal('image'));
        }
        $imagePath      = $request->file('image')->store('profilePhotos', 'public');
        $student->image = $imagePath;
        $student->save();
        return response()->json([
            'status'  => true,
            'message' => 'Profile photo updated successfully',
            'student' => $student,
        ]);
    }

    public function checkIfEmailExists(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $exists = Students::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json([
                'status'  => true,
                'message' => 'Email already exists',
            ]);
        }
        return response()->json([
            'status'  => true,
            'exists'  => $exists,
            'message' => 'Email is available',
        ]);
    }
}
