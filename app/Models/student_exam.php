<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student_exam extends Model
{
    protected $guarded = [];

    public function student_exam_answer()
    {
        return $this->hasMany(student_exam_answer::class);
    }

    public function exam()
    {
        return $this->belongsTo(exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Students::class);
    }
}
