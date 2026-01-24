<?php
namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\ads;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index()
    {
        $teachers = Teacher::query()->where('state', true)->take(6)->get();
        $banners  = ads::where('type', 'sliders')->where('status', true)->get();
        return view('website.home', ['teachers' => $teachers, 'banners' => $banners]);
    }

    public function teacherProfile($id)
    {
        $teacher = Teacher::find($id);
        return view('website.teacher-profile', ['teacher' => $teacher]);
    }
}
