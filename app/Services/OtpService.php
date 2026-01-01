<?php
namespace App\Services;

use App\Mail\MailsSendOtpMail;
use App\Models\otp;
use App\Models\Students;
use App\Models\StudentsLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    // توليد الـ OTP
    public function generate($length = 6)
    {
        return rand(pow(10, $length - 1), pow(10, $length) - 1);
    }

    // إنشاء وتخزين OTP
    public function createOtp($identifier)
    {
        $otp = $this->generate();
        otp::create([
            'identifier' => $identifier,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
        return $otp;
    }

    // إرسال OTP (إيميل أو SMS)
    public function sendOtp($identifier)
    {
        $otp = $this->createOtp($identifier);

        // 🔵 مثال إرسال إيميل (تقدر تبدله SMS)
        Mail::to($identifier)->send(new MailsSendOtpMail($otp));

        return $otp; // رجّعه علشان تستخدمه في Testing لو عايز
    }

    // التحقق من الـ OTP
    public function verifyOtp($identifier, $otp)
    {
        $otpRecord = otp::where('identifier', $identifier)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        if (! $otpRecord) {
            return false;
        }
        // تحديث الحالة إن OTP تم استخدامه
        $otpRecord->update(['is_used' => true]);
        $student = Students::where('email', $identifier)->first();
        $student->update(['status' => true]);
        return true;
    }

    public function AnotherDevice($id)
    {
        $get_student_log = StudentsLogs::query()
            ->where('student_id', $id)
            ->where('is_active', true)
            ->latest()->first();
        if ($get_student_log) {
            // User is already logged in from another device
            return true;
        }
        return false;
    }
}
