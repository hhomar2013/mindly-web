<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationStage;
use App\Models\SecondaryBranch;
use App\Models\SecondaryGrade;
use App\Models\SecondarySpecialization;
use App\Models\SecondarySubBranch;
use App\Models\SecondaryTrack;
use App\Models\secondary_student_details;
use App\Models\StageGrade;
use App\Models\Students;
use App\Models\StudentsLogs;
use App\Models\UniversityAcademicYear;
use App\Models\UniversityFaculty;
use App\Models\UniversityInstitute;
use App\Models\universty_student_details;
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
    } //Login

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
    } //LoginSendOtp

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
    } //Profile

                                               // ✅ Logout student (revoke token)
    public function logout(Request $request)
    { //Logout
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


    public function store(Request $request, OtpService $otpService)
    {
        // 1. Validation الأساسي
        $request->validate([
            'name'           => 'required',
            'email'          => 'required|email|unique:students,email',
            'password'       => 'required|min:6',
            'governorate_id' => 'required|exists:governors,id',
            'city_id'        => 'required|exists:cities,id',
        ]);

        DB::beginTransaction();
        try {
            $educationMap     = config('education_phases');
            $educationTypeKey = $request->education_type_key;

            if (! isset($educationMap[$educationTypeKey])) {
                return response()->json(['message' => 'Invalid education phase key.'], 422);
            }

            $morphClass = $educationMap[$educationTypeKey];
            $morphId    = null;

            // --- التعامل مع المرحلة الثانوية ---
            if ($educationTypeKey === 'secondary') {
                $request->validate([
                    'secondary_track_key'          => ['required', 'exists:secondary_tracks,track_id'],
                    'secondary_grade_key'          => ['required', 'exists:secondary_grades,grade_id'],
                    'secondary_branch_key'         => ['nullable', 'exists:secondary_branches,branch_id'],
                    'secondary_sub_branch_key'     => ['nullable', Rule::exists('secondary_sub_branches', 'sub_branch_id')],
                    'secondary_specialization_key' => ['nullable', 'exists:secondary_specializations,spec_id'],
                ]);

                $secondaryDetail = secondary_student_details::create([
                    'secondary_track_id'          => SecondaryTrack::where('track_id', $request->secondary_track_key)->value('id'),
                    'secondary_grade_id'          => SecondaryGrade::where('grade_id', $request->secondary_grade_key)->value('id'),
                    'secondary_branch_id'         => $request->secondary_branch_key ? SecondaryBranch::where('branch_id', $request->secondary_branch_key)->value('id') : null,
                    'secondary_sub_branch_id'     => $request->secondary_sub_branch_key ? SecondarySubBranch::where('sub_branch_id', $request->secondary_sub_branch_key)->value('id') : null,
                    'secondary_specialization_id' => $request->secondary_specialization_key ? SecondarySpecialization::where('spec_id', $request->secondary_specialization_key)->value('id') : null,
                ]);
                $morphId = $secondaryDetail->id;

                // --- التعامل مع المرحلة الجامعية (التعديل الجديد) ---
            } elseif ($educationTypeKey === 'undergraduate') {
                $universityType = $request->university_type;
                if (! in_array($universityType, ['institute', 'faculty'])) {
                    return response()->json(['message' => 'Invalid university type.'], 422);
                }

                $yearId = UniversityAcademicYear::where('year_number', $request->university_academic_year_key)
                    ->firstOrFail(['id'])->id;
                $educationStageId = EducationStage::where('stage_id', 'undergraduate')->firstOrFail(['id'])->id;
                $facultyId        = null;
                $instituteId      = null;

                $rules = [
                    'university_academic_year_key' => 'nullable|exists:university_academic_years,id',
                ];

                if ($universityType === 'institute') {
                    $instituteId = UniversityInstitute::where('institute_id', $request->university_institute_key)
                        ->firstOrFail(['id'])->id;
                } elseif ($universityType === 'faculty') {
                    $facultyId = UniversityFaculty::where('faculty_id', $request->university_faculty_key)
                        ->firstOrFail(['id'])->id;
                }

                $request->validate($rules);

                $universityDetail = universty_student_details::create([
                    'education_stage_id'          => $educationStageId,
                    'university_faculty_id'       => $facultyId,
                    'university_institute_id'     => $instituteId,
                    'university_academic_year_id' => $yearId,
                ]);
                $morphId = $universityDetail->id;

                // --- ابتدائي وإعدادي ---
            } elseif ($educationTypeKey === 'primary' || $educationTypeKey === 'preparatory') {
                $request->validate([
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

            return response()->json(['status' => true, 'message' => 'OTP sent successfully.'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => $e->getMessage()], 422);
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'     => 'required',
            'new_password'         => 'required|min:6|different:current_password',
            'new_password_confirm' => 'required|same:new_password',
        ]);
        $student = $request->user();
        if (! Hash::check($request->current_password, $student->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $student->password = bcrypt($request->new_password);
        $student->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully',
        ]);
    }
    
    public function deleteAccount(Request $request)
    {
        $request->user()->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Account deleted successfully',
        ]);
    }

}
