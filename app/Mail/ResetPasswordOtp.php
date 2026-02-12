<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // الكود الذي سيتم إرساله

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('كود استعادة كلمة المرور - مايندلي')
            ->view('email.reset_password_otp');
    }
}
