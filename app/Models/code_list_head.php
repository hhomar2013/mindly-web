<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class code_list_head extends Model
{
    protected $guarded = [];

    public function teacherCourseOverview()
    {
        return $this->belongsTo(TeacherCourseOverview::class ,'teacher_course_overviews_id');
    }

    public function codeList()
    {
        return $this->hasMany(code_list_body::class);
    }
}
