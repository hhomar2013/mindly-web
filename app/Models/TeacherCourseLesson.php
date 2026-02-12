<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeacherCourseLesson extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded         = [];
    public array $translatable = ['name'];


    public function CourseOverview()
    {
        return $this->belongsTo(TeacherCourseOverview::class,'tco_id');
    }

    public function CourseLessonContent()
    {
        return $this->hasMany(TeacherCourseLessonContent::class,'tcl_id','id');
    }


    public function PurchaseOption()
    {
        return $this->hasMany(PurchaseOption::class);
    }
}
