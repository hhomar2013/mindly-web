<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Translatable\HasTranslations;

class TeacherCourseOverview extends Model
{
    use HasFactory, HasTranslations;
    protected $table = "teacher_course_overviews";
    protected $guarded = [];
    protected $translatable = ['name'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function contents()
    {
        return $this->hasMany(TeacherCourseLessonContent::class, 'tcl_id');
    }

    // public function purchaseOptions()
    // {
    //     return $this->morphMany(PurchaseOption::class, 'purchaseable');
    // }


    public function subject()
    {
        return $this->belongsTo(subjects::class);
    }

    public function education()
    {
        return $this->morphTo('education');
    }

    public function reviews()
    {
        return $this->hasMany(TeacherCourseReview::class, 'tco_id');
    }


    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
