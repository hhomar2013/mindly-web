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
        return $centers;
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
          // return response()->json([
          //     'message' => 'Top Rated Teachers ✔️',
          //     'count' => $teachers->count(),
          //     'data' => $data
          // ], 200);
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

        // $ads            = $this->ads();
        // $topRated       = $this->topRatedTeachers();
        // $userCity       = $this->getUserCity($request);
        // $teachersByCity = $this->getTeachersByCity($userCity);
        // return response()->json([
        //     'message'  => 'Home Page Sections ✔️',
        //     'teachers' =>
        //     [

        //         'name_en' => 'Top Rated Teachers',
        //         'name_ar' => "المدرسين الأكثر شهره",
        //         'data'    => $topRated,

        //         'name_en' => 'Teachers in your city',
        //         'name_ar' => "المدرسين الأقرب لمنطقتك",
        //         'data'    => $teachersByCity->map(function ($val) {
        //             return [
        //                 'id'            => $val->id,
        //                 'name_ar'       => $val->getTranslation('name', 'ar'),
        //                 'name_en'       => $val->getTranslation('name', 'en'),
        //                 'image'         => $val->image ? asset('storage/' . $val->image) : null,
        //                 'Courses Count' => $val->teacherCourseOverview->count(),
        //             ];
        //         }),
        //     ],
        //     'Centers'  =>
        //     [
        //         'name_en' => 'Centers',
        //         'name_ar' => "المراكز التعليميه",
        //         'data'    => $this->centers(),
        //     ],
        //     [
        //         'name_en' => 'Advertisements',
        //         'name_ar' => "الإعلانات",
        //         'data'    => $ads,
        //     ],

        // ], 200);

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

}
