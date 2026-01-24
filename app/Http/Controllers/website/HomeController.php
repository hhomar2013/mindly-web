<?php
namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Mail\ContactUsMail;
use App\Models\ads;
use App\Models\Center;
use App\Models\Teacher;
use App\Models\TermAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $teachers = Teacher::query()
            ->where('state', true)
            ->orderBy('rating_system', 'desc')
            ->take(6)->get();
        $banners = ads::where('type', 'sliders')

            ->where('status', true)->get();
        $centers = Center::query()->where('state', true)->take(6)->get();
        return view('website.home', ['teachers' => $teachers, 'banners' => $banners, 'centers' => $centers]);
    }

    public function teacherProfile($id)
    {
        $teacher = Teacher::find($id);
        return view('website.teacher-profile', ['teacher' => $teacher]);
    }

    public function sendEmail(Request $request)
    {
        // 1. التحقق من البيانات (Validation)
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. إرسال الإيميل
        Mail::to('info@mindlyedu.com')->send(new ContactUsMail($data));

        // 3. الرجوع برسالة نجاح
        return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
    }

    public function Terms()
    {
        $terms   = TermAndCondition::current('terms');
        $privacy = TermAndCondition::current('privacy');
        return view('website.terms_condetions', ['terms' => $terms, 'privacy' => $privacy]);
    }
}
