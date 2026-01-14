<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\enrolling_students;
use App\Models\TeacherCourseLesson;
use App\Models\TeacherCourseOverview;
use App\Models\TeacherCourseReview;
use Illuminate\Http\Request;

class coursesCotroller extends Controller
{

    public function index()
    {
        $courses = TeacherCourseOverview::query()->with(['teacher'])->get();

        $data = [
            'courses' => $courses->map(function ($course) {
                return [
                    'id'         => $course->id,
                    'subject_id' => $course->subject_id,
                    'name_ar'    => $course->getTranslation('name', 'ar'),
                    'name_en'    => $course->getTranslation('name', 'en'),
                    'teacher_id' => $course->teacher_id,
                    'image'      => $course->image,
                    'demoVideo'  => $course->optional_link ?? null,
                    'teacher'    => [
                        'teacher_name_ar' => $course->teacher->getTranslation('name', 'ar'),
                        'teacher_name_en' => $course->teacher->getTranslation('name', 'en'),
                        'teacher_image'   => $course->teacher->image ? asset('storage/' . $course->teacher->image) : null,
                        'governor'        => ($course->teacher->cities && $course->teacher->cities->Governorates) ? [
                            'id'      => $course->teacher->cities->Governorates->id,
                            'name_ar' => $course->teacher->cities->Governorates->getTranslation('name', 'ar'),
                            'name_en' => $course->teacher->cities->Governorates->getTranslation('name', 'en'),
                        ] : null,
                        'city'            => $course->teacher->cities ? [
                            'id'      => $course->teacher->cities->id,
                            'name_ar' => $course->teacher->cities->getTranslation('name', 'ar'),
                            'name_en' => $course->teacher->cities->getTranslation('name', 'en'),
                        ] : null,
                    ],
                ];
            }),
        ];
        return response()->json([
            'message' => 'Courses ✔️',
            'data'    => $data,
        ], 200);
    }

    public function check_user_inrolled(Request $request, $courseId)
    {
        return enrolling_students::query()
            ->where('student_id', $request->user()->id)
            ->where('is_completed', 0)
            ->whereHas('code.codeListHead.teacherCourseOverview', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            })
            ->with('code.codeListHead.teacherCourseOverview')
            ->first();
    }

    public function show_course_lessons(Request $request)
    {
        $is_enrolled = false;
        $enrolled    = $this->check_user_inrolled($request, $request->id);

        if ($enrolled) {
            $is_enrolled = true;
        } else {
            $is_enrolled = false;
        }

        $course = TeacherCourseLesson::query()->with(['CourseOverview', 'CourseLessonContent'])->where('tco_id', $request->id)->get();
        if ($course) {
            $Overview = null;
            foreach ($course as $lessons) {
                $Overview = $lessons->CourseOverview;
            }

            if ($course->isEmpty()) {
                return response()->json([
                    'message' => 'Course not found',
                    'data'    => null,
                ], 404);
            }

            // جيب الـ Overview من أول عنصر
            $Overview = $course->first()?->CourseOverview;

            $data = [
                'is_enrolled' => $is_enrolled,
                'Overview'    => $Overview ? [
                    'id'         => $Overview->id,
                    'subject_id' => $Overview->subject_id,
                    'name_ar'    => $Overview->getTranslation('name', 'ar'),
                    'name_en'    => $Overview->getTranslation('name', 'en'),
                    'teacher_id' => $Overview->teacher_id,
                    'teacher'    => [
                        'teacher_name_ar' => $Overview->teacher->getTranslation('name', 'ar'),
                        'teacher_name_en' => $Overview->teacher->getTranslation('name', 'en'),
                        'teacher_image'   => $Overview->teacher->image ? asset('storage/' . $Overview->teacher->image) : null,
                        'governor'        => ($Overview->teacher->city && $Overview->teacher->city->Governorates) ? [
                            'id'      => $Overview->teacher->city->Governorates->id,
                            'name_ar' => $Overview->teacher->city->Governorates->getTranslation('name', 'ar'),
                            'name_en' => $Overview->teacher->city->Governorates->getTranslation('name', 'en'),
                        ] : null,
                        'city'            => $Overview->teacher->cities ? [
                            'id'      => $Overview->teacher->cities->id,
                            'name_ar' => $Overview->teacher->cities->getTranslation('name', 'ar'),
                            'name_en' => $Overview->teacher->cities->getTranslation('name', 'en'),
                        ] : null,
                    ],
                ] : null,
                'lessons'     => $course->map(function ($lesson) {
                    return [
                        'id'      => $lesson->id,
                        'name_ar' => $lesson->getTranslation('name', 'ar'),
                        'name_en' => $lesson->getTranslation('name', 'en'),
                        'content' => $lesson->CourseLessonContent->map(function ($content) {
                            $type = $content->contentType?->type;
                            return [
                                'id'          => $content->id,
                                'ContentType' => $content->contentType ? [
                                    'id'      => $content->contentType->id,
                                    'name_ar' => $content->contentType->getTranslation('name', 'ar'),
                                    'name_en' => $content->contentType->getTranslation('name', 'en'),
                                    'icon'    => $content->contentType->icon ? asset('storage/' . $content->contentType->icon) : null,
                                    'type'    => $type,
                                ] : null,
                                'name_ar'     => $content->getTranslation('name', 'ar'),
                                'name_en'     => $content->getTranslation('name', 'en'),
                                'link'        => $type === 'link' || $type === 'quiz' ? $content->link : asset($content->link),
                            ];
                        }),
                    ];
                }),
                'reviews'     => ($Overview && $Overview->reviews) ? [
                    'count'   => $Overview->reviews->count(),
                    'student' => $Overview->reviews->map(function ($review) {
                        if ($review->student) {
                            return [
                                'id'      => $review->student->id,
                                'name'    => $review->student->name,
                                'rating'  => $review->star_number,
                                'image'   => $review->student->image ? asset('storage/' . $review->student->image) : null,
                                'comment' => $review->content,
                            ];
                        }
                        return ['No Reviews Found yet'];
                    }),
                ] : null,
            ];

            return response()->json([
                'message' => 'Course ✔️',
                'data'    => $data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Course not found',
                'data'    => null,
            ], 404);
        }
    }

    private function checkReviewCount(Request $request, $courseId)
    {
        $userId = $request->user()->id;
        return TeacherCourseReview::query()->where('tco_id', $courseId)->where('student_id', $userId)->count();
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:teacher_course_overviews,id',
            'star_number' => 'required|numeric|between:1,5',
            'content'     => 'required|string',
        ]);
        $enrolled = $this->check_user_inrolled($request, $request->course_id);
        if (! $enrolled) {
            return response()->json([
                'message' => 'You are not enrolled in this course',
                'data'    => null,
            ], 401);
        }

        $crc = $this->checkReviewCount($request, $request->course_id);
        if ($crc > 0) {
            return response()->json([
                'message' => 'You have already reviewed this course',
                'data'    => null,
            ], 401);
        }
        $review = TeacherCourseReview::create([
            'tco_id'      => $request->course_id,
            'student_id'  => $request->user()->id,
            'star_number' => $request->star_number,
            'content'     => $request->content,
        ]);

        if (! $review) {
            return response()->json([
                'message' => 'Failed to add review',
                'data'    => null,
            ], 500);
        }
        return response()->json([
            'message' => 'Review added successfully ✔️👌',
            'data'    => $review,
        ], 201);
    } //Store Reviews
}
