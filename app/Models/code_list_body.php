<?php

namespace App\Models;

use App\Helpers\QrGeneration;
use Illuminate\Database\Eloquent\Model;

class code_list_body extends Model
{
    use QrGeneration;

    protected $guarded = [];
    // protected $appends = ['qr_code'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = self::generateNumericCode(14);
        });
    }

    public static function generateNumericCode($length = 14)
    {
        $min = str_pad('1', $length, '0', STR_PAD_RIGHT);   // First digit not zero
        $max = str_repeat('9', $length);

        return (string) random_int((int)$min, (int)$max);
    }

    public function getQrCodeAttribute()
    {
        return $this->generateQrBase64($this->code);
    }


    public function tos()
    {
        return $this->belongsTo(type_of_subscriptions::class, 'type_of_subscription_id', 'id');
    }

    public function teacherCourseOverview()
    {
        return $this->belongsTo(teacherCourseOverview::class, 'teacher_course_overview_id', 'id');
    }

    public function codeListHead(){
        return $this->belongsTo(code_list_head::class, 'code_list_head_id', 'id');
    }
}
