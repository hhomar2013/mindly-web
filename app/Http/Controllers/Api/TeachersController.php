<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\Center;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeachersController extends Controller
{

    public function centers()
    {
        $centers = Center::query()->where('state', 1)->get();
        $data = [];
        foreach ($centers as $center) {
            $data[] = [
                'id'   => $center->id,
                'name_ar' => $center->getTranslation('name', 'ar'),
                'name_en' => $center->getTranslation('name', 'ar'),
                'phone' => $center->phone,
                'address' => $center->address,
                'governorate' => $center->cities->Governorates,
                'city' => $center->cities,
                'image' => $center->image ? asset('storage/' . $center->image) : null,
                'main_info' => $center->main_info,
                'welcome_message' => $center->welcome_message,
            ];
        }
        return $data;
    }

    public function ads()
    {
        $ads  = ads::query()->where('status', 1)->get();
        $data = [];
        foreach ($ads as $val) {
            $data[] = [
                'type'    => $val->type,
                'from'    => $val->from,
                'image'   => $val->image ? asset('storage/' . $val->image) : null,
                'link'    => $val->link,
                'comment' => $val->comment,
            ];
        }

        return $data;
    }
    public function topRatedTeachers()
    {
        $teachers = Teacher::query()->where('state', 1)
            ->where('in_out', 1)
            ->where('rating_system', '>=', 4)
            ->orderBy('rating_system', 'desc')
            ->with('teacherCourseOverview')
            ->get();
        $data = [];
        foreach ($teachers as $teacher) {
            $data[] = [
                'id'            => $teacher->id,
                'name_ar'       => $teacher->getTranslation('name', 'ar'),
                'name_en'       => $teacher->getTranslation('name', 'en'),
                'image'         => $teacher->image ? asset('storage/' . $teacher->image) : null,
                'rating_system' => $teacher->rating_system,
                'Courses Count' => $teacher->teacherCourseOverview->count(),
            ];
        }
        return $data;
    } //Top reated teachers

    public function getUserCity(Request $request)
    {
        return $request->user()->city_id;
    } // getUserCity

    public function getGovernorate(Request $request)
    {
        return $request->user()->governorate_id;
    }

    public function getTeachersByCity($city_id)
    {
        return Teacher::query()->where('city_id', $city_id)->with('teacherCourseOverview')->get();
    }

    public function homePage(Request $request)
    {
        $ads            = $this->ads();
        $topRated       = $this->topRatedTeachers();
        $userCity       = $this->getUserCity($request);
        $teachersByCity = $this->getTeachersByCity($userCity);

        return response()->json([
            'message'  => 'Home Page Sections ✔️',

            'teachers' => [
                [
                    'name_en' => 'Top Rated Teachers',
                    'name_ar' => 'المدرسين الأكثر شهره',
                    'data'    => $topRated,
                ],
                [
                    'name_en' => 'Teachers in your city',
                    'name_ar' => 'المدرسين الأقرب لمنطقتك',
                    'data'    => $teachersByCity->map(function ($val) {
                        return [
                            'id'            => $val->id,
                            'name_ar'       => $val->getTranslation('name', 'ar'),
                            'name_en'       => $val->getTranslation('name', 'en'),
                            'image'         => $val->image ? asset('storage/' . $val->image) : null,
                            'rating_system' => $val->rating_system ?? 0,
                            'Courses Count' => $val->teacherCourseOverview->count(),
                        ];
                    }),
                ],
            ],

            'Centers'  => [
                'name_en' => 'Centers',
                'name_ar' => 'المراكز التعليميه',
                'data'    => $this->centers(),
            ],
            'ads'      => [
                'name_en' => 'Advertisements',
                'name_ar' => 'الإعلانات',
                'data'    => $ads,
            ],

        ], 200);
    } // teachersByCities

    public function TeacherProfile(Request $request)
    {
        $teacher_id = $request->teacher_id;
        $teacher    = Teacher::query()->where('id', $teacher_id)
            ->with('teacherCourseOverview', 'cities', 'cities.governorates')
            ->first();
        if (! $teacher) {
            return response()->json([
                'message' => 'Teacher not found ❌',
            ], 404);
        }

        return response()->json([
            'message' => 'Teacher Profile ✔️',
            'data'    => [
                'id'            => $teacher->id,
                'name_ar'       => $teacher->getTranslation('name', 'ar'),
                'name_en'       => $teacher->getTranslation('name', 'en'),
                'image'         => $teacher->image ? asset('storage/' . $teacher->image) : null,
                'address'       => $teacher->address,
                'phone'         => $teacher->phone,
                'decription'    => $teacher->description,
                'governorate'   => $teacher->cities && $teacher->cities->governorates ? [
                    'id'      => $teacher->cities->governorates->id,
                    'name_ar' => $teacher->cities->governorates->getTranslation('name', 'ar'),
                    'name_en' => $teacher->cities->governorates->getTranslation('name', 'en'),
                ] : null,
                'city'          => $teacher->cities ? [
                    'id'      => $teacher->cities->id,
                    'name_ar' => $teacher->cities->getTranslation('name', 'ar'),
                    'name_en' => $teacher->cities->getTranslation('name', 'en'),
                ] : null,

                'Courses Count' => $teacher->teacherCourseOverview->count(),
                'courses'       => $teacher->teacherCourseOverview->map(function ($course) {
                    return [
                        'id'      => $course->id,
                        'name_ar' => $course->getTranslation('name', 'ar'),
                        'name_en' => $course->getTranslation('name', 'en'),
                        'image'   => $course->image ? $course->image : null
                    ];
                }),
                'rating_system' => $teacher->rating_system ?? 0
            ],
        ], 200);
    } // TeacherProfile

    public function CenterProfile(Request $request)
    {
        $data      = [];
        $center_id = $request->center_id;
        $center    = Center::query()->where('id', $center_id)->first();
        if (! $center) {
            return response()->json([
                'message' => 'Center not found ❌',
            ], 404);
        }
        $data = [
            'id'              => $center->id,
            'name_ar'         => $center->getTranslation('name', 'ar'),
            'name_en'         => $center->getTranslation('name', 'en'),
            'image'           => $center->logo ? asset('storage' . $center->logo) : null,
            'panner'          => $center->panner ? asset('storage' . $center->panner) : null,
            'address'         => $center->address,
            'phone'           => $center->phone,
            'city'            => $center->cities ? [
                'id'      => $center->cities->id,
                'name_ar' => $center->cities->getTranslation('name', 'ar'),
                'name_en' => $center->cities->getTranslation('name', 'en'),
            ] : null,
            'welcome_message' => $center->welcome_message,
            'main_info'       => $center->main_info,
            'teachers_count'  => $center->centerTeachers->count(),
            'teachers'        => $center->centerTeachers->map(function ($teacher) {
                return [
                    'id'      => $teacher->teachers->id,
                    'name_ar' => $teacher->teachers->getTranslation('name', 'ar'),
                    'name_en' => $teacher->teachers->getTranslation('name', 'en'),
                    'image'   => $teacher->teachers->image ? asset('storage/' . $teacher->teachers->image) : null,
                ];
            }),
        ];
        return response()->json([
            'message' => 'Center Profile ✔️',
            'data'    => $data,
        ], 200);
    }
}
