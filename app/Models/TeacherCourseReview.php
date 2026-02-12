<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherCourseReview extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(TeacherCourseOverview::class, 'tco_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
