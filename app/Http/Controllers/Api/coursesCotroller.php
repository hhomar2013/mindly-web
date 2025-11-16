<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherCourseLesson;
use App\Models\TeacherCourseOverview;
use Illuminate\Http\Request;

class coursesCotroller extends Controller
{
    public function index()
    {
        $courses = TeacherCourseOverview::query()->with(['teacher'])->get();

        $data = [
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'subject_id' => $course->subject_id,
                    'name_ar' => $course->getTranslation('name', 'ar'),
                    'name_en' => $course->getTranslation('name', 'en'),
                    'teacher_id' => $course->teacher_id,
                    'teacher' => [
                        'teacher_name_ar' => $course->teacher->getTranslation('name', 'ar'),
                        'teacher_name_en' => $course->teacher->getTranslation('name', 'en'),
                        'teacher_image' => $course->teacher->image,
                        'governor' => ($course->teacher->cities && $course->teacher->cities->Governorates) ? [
                            'id' => $course->teacher->cities->Governorates->id,
                            'name_ar' => $course->teacher->cities->Governorates->getTranslation('name', 'ar'),
                            'name_en' => $course->teacher->cities->Governorates->getTranslation('name', 'en'),
                        ] : null,
                        'city' => $course->teacher->cities ? [
                            'id' => $course->teacher->cities->id,
                            'name_ar' => $course->teacher->cities->getTranslation('name', 'ar'),
                            'name_en' => $course->teacher->cities->getTranslation('name', 'en'),
                        ] : null,
                    ],
                ];
            }),
        ];
        return response()->json([
            'message' => 'Courses ✔️',
            'data' => $data
        ], 200);
    }


    public function show_course_lessons(Request $request)
    {
        $course = TeacherCourseLesson::query()->with(['CourseOverview', 'CourseLessonContent'])->where('tco_id', $request->id)->get();
        if ($course) {
            $Overview = null;
            foreach ($course as $lessons) {
                $Overview = $lessons->CourseOverview;
            }
            $data = [
                'Overview' => $Overview ? [
                    'id' => $Overview->id,
                    'subject_id' => $Overview->subject_id,
                    'name_ar' => $Overview->getTranslation('name', 'ar'),
                    'name_en' => $Overview->getTranslation('name', 'en'),
                    'teacher_id' => $Overview->teacher_id,
                    'teacher' => [
                        'teacher_name_ar' => $Overview->teacher->getTranslation('name', 'ar'),
                        'teacher_name_en' => $Overview->teacher->getTranslation('name', 'en'),
                        'teacher_image' => $Overview->teacher->image,
                        'governor' => ($Overview->teacher->city && $Overview->teacher->city->Governorates) ? [
                            'id' => $Overview->teacher->city->Governorates->id,
                            'name_ar' => $Overview->teacher->city->Governorates->getTranslation('name', 'ar'),
                            'name_en' => $Overview->teacher->city->Governorates->getTranslation('name', 'en'),
                        ] : null,
                        'city' => $Overview->teacher->cities ? [
                            'id' => $Overview->teacher->cities->id,
                            'name_ar' => $Overview->teacher->cities->getTranslation('name', 'ar'),
                            'name_en' => $Overview->teacher->cities->getTranslation('name', 'en'),
                        ] : null,
                    ],
                ] : null,
                'lessons' => $course->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'name_ar' => $lesson->getTranslation('name', 'ar'),
                        'name_en' => $lesson->getTranslation('name', 'en'),
                        'content' => $lesson->CourseLessonContent->map(function ($content) {
                            $type = $content->contentType?->type;
                            return [
                                'id' => $content->id,
                                'ContentType' => $content->contentType ? [
                                    'id' => $content->contentType->id,
                                    'name_ar' => $content->contentType->getTranslation('name', 'ar'),
                                    'name_en' => $content->contentType->getTranslation('name', 'en'),
                                    'icon' => asset('storage/' . $content->contentType->icon),
                                    'type' => $type,
                                ] : null,
                                'name_ar' => $content->getTranslation('name', 'ar'),
                                'name_en' => $content->getTranslation('name', 'en'),
                                'link' => $type === 'link' ? $content->link : asset($content->link),
                            ];
                        }),
                    ];
                }),
                'reviews' => $Overview->reviews ? [
                    'count' => $Overview->reviews->count(),
                    'student' => $Overview->reviews->map(function ($review) {
                        return [
                            'id' => $review->student->id,
                            'name' => $review->student->name,
                            'rating' => $review->star_number,
                            'image' => $review->student->image ? asset('storage/' . $review->student->image) : null,
                            'comment' => $review->content,
                        ];
                    }),
                ] : null,
            ];

            return response()->json([
                'message' => 'Course ✔️',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found',
                'data' => null
            ], 404);
        }
    }
}
