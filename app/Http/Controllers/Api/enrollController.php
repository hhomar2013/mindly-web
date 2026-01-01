<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\code_list_body;
use App\Models\enrolling_students;
use Carbon\Carbon;
use Illuminate\Http\Request;

class enrollController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $enrollCoursesCheck = [];
        $enrollCoursesCheck = enrolling_students::query()->where('student_id', $user->id)->get();
        foreach ($enrollCoursesCheck as $val) {
            $completed_at = Carbon::parse($val->completed_at)->format('Y-m-d');
            $completed_at <= Carbon::now()->format('Y-m-d') ? $val->is_completed = true : $val->is_completed = false;
            $val->save();
        }
        // $enrollCourses = enrolling_students::query()
        //     ->where('student_id', $user->id)
        //     ->where('is_completed', false)
        //     ->with('code.codeListHead.teacherCourseOverview')
        //     ->get();
        $overviews = enrolling_students::query()
            ->where('student_id', $user->id)
            ->where('is_completed', false)
            ->with('code.codeListHead.teacherCourseOverview')
            ->get()
            ->map(function ($item) {
                $val = $item->code->codeListHead->teacherCourseOverview;
                return  $val ? [
                    "id" => $val->id,
                    // "education" => $val->education,
                    "name_ar" => $val->getTranslation('name', 'ar'),
                    "name_en" => $val->getTranslation('name', 'en'),
                    "image" => $val->image,
                ] : null;
            });

        if ($overviews->isEmpty()) {
            return response()->json([
                'message' => 'Enrolled courses retrieved successfully ✔️👌',
                'data' =>  "You Haven't any courses",
            ], 200);
        }

        return response()->json([
            'message' => 'Enrolled courses retrieved successfully ✔️👌',
            'My Courses' => 'الدروس الخاصه بي',
            'data' => $overviews,
        ], 200);
    }

    public function enrollCourse(Request $request)
    {
        $request->validate([
            'cardCode' => 'required',
        ]);
        $user_id = $request->user()->id;
        $check_code = [];
        $check_code = code_list_body::query()
            ->where('code', $request->cardCode)
            ->where('is_used', 0)
            ->with('tos')->first();
        if (!$check_code) {
            return response()->json([
                'error' => 'This code is already used! 🚫',
            ], 400);
        }

        $get_duration = $check_code->tos->duration;
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
        return response()->json([
            'message' => 'Course enrolled successfully ✔️👌',
            'data' =>  $create_enrolling,
        ], 201);
    }
}
