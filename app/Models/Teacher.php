<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Teacher extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded         = [];
    public array $translatable = ['name'];

    public function wallet()
    {
        return $this->hasOne(teacher_wallets::class, 'teacher_id', 'id');
    }



    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function codeList()
    {
        return $this->hasMany(code_list_head::class, 'teacher_id', 'id');
    }

    public function teacherCourseOverview()
    {
        return $this->hasMany(TeacherCourseOverview::class, 'teacher_id', 'id');
    }

    public function quizes()
    {
        return $this->hasMany(exam::class, 'teacher_id', 'id');
    }

    
}
