<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TeacherCourseLessonContent extends Model
{
    use HasFactory, HasTranslations;

    protected $translatable = ['name'];
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(TeacherCourseLesson::class, 'tcl_id');
    }

    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'ct_id');
    }

    public function purchaseOptions()
    {
        return $this->morphMany(PurchaseOption::class, 'purchaseable');
    }
}
