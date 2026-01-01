<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use Illuminate\Http\Request;

use function Livewire\store;
use function PHPUnit\Framework\isEmpty;

class searchController extends Controller
{
    public function search(Request $request)
    {
        $lang = "ar";
        config(['app.locale' => $lang]);
        $search = $request->search;
        $Teachers = [];
        $Centers = [];
        $Courses = [];
        if ($search == "") {
            return response()->json([
                'message' => 'No search term provided',
            ], 400);
        }
        $Teachers = Teacher::where('name', 'like', "%" . $search . "%")->with('cities')->orderBy('rating_system', 'desc')->get();
        $Centers = Center::where('name', 'like', "%" . $search . "%")->with('cities')->get();
        $Courses = TeacherCourseOverview::where('name', 'like', "%" . $search . "%")->get();


        return response()->json([

            'teachers' => $Teachers ? [
                'data' => $Teachers->map(function ($teacher) {
                    return [
                        'nameAr' => $teacher->getTranslation('name', 'ar') ?? null,
                        'nameEn' => $teacher->getTranslation('name', 'en') ?? null,
                        'governorate' => $teacher->cities->Governorates ? $teacher->cities->Governorates->getTranslation('name', 'ar') : null,
                        'city' => $teacher->cities ? $teacher->cities->getTranslation('name', 'ar') : null,
                        'image' => asset('storage/' . $teacher->image) ?? null,
                        'Link' => "/teacher-profile/" . $teacher->id,
                    ];
                }),
                'count' => $Teachers->count(),
            ] : [],
            'centers' => $Centers->map(function ($center) {
                return [
                    'nameAr' => $center->getTranslation('name', 'ar') ?? null,
                    'nameEn' => $center->getTranslation('name', 'en') ?? null,
                    'governorate' => $center->cities->Governorates ? $center->cities->Governorates->getTranslation('name', 'ar') : null,
                    'city' => $center->cities ? $center->cities->getTranslation('name', 'ar') : null,
                    'logo' => asset('storage/' . $center->logo),
                    'Link' => "/center-profile/" . $center->id,
                ];
            }),
            'courses' => $Courses->map(function ($course) {
                return [
                    'nameAr' => $course->getTranslation('name', 'ar') ?? null,
                    'nameEn' => $course->getTranslation('name', 'en') ?? null,
                    'image' => $course->image ?? null,
                    'Link' => "/course/" . $course->id,
                ];
            }),
        ]);
    }
}
