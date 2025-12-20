<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student_exam_answer extends Model
{
    protected $guarded = [];
    public function student_exam()
    {
        return $this->belongsTo(student_exam::class);
    }
}
