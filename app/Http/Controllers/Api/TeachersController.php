<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
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
                'id' => $teacher->id,
                'name_ar' => $teacher->getTranslation('name', 'ar'),
                'name_en' => $teacher->getTranslation('name', 'en'),
                'image' => $teacher->image ? asset('storage/' . $teacher->image) : null,
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

    private function getUserCity(Request $request)
    {
        return $request->user()->city_id;
    } // getUserCity

    private function getGovernorate(Request $request)
    {
        return $request->user()->governorate_id;
    }

    private function getTeachersByCity($city_id)
    {
        return Teacher::query()->where('city_id', $city_id)->with('teacherCourseOverview')->get();
    }

    public function teachersByCities(Request $request)
    {
        $topRated = $this->topRatedTeachers();
        $userCity = $this->getUserCity($request);
        $teachersByCity = $this->getTeachersByCity($userCity);
        return response()->json([
            'message' => 'Teachers ✔️',
            'Top Rated Teachers' => "المدرسين الأكثر شهره",
            'topRatedTeachers' => $topRated,
            'Teachers in your city' => "المدرسين الأقرب لمنطقتك",
            'teachersByCities' => $teachersByCity->map(function ($val) {
                return [
                    'id' => $val->id,
                    'name_ar' => $val->getTranslation('name', 'ar'),
                    'name_en' => $val->getTranslation('name', 'en'),
                    'image' => $val->image ? asset('storage/' . $val->image) : null,
                    'Courses Count' => $val->teacherCourseOverview->count()
                ];
            }),
        ], 200);
    } // teachersByCities


}
