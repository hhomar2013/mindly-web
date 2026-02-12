<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class UniversityFaculty extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded      = [];
    protected $translatable = ['name'];

    public function system()
    {
        return $this->belongsTo(EducationSystem::class, 'education_system_id');
    }

    public function universityDetails()
    {
        return $this->hasMany(universty_student_details::class, 'university_faculty_id');
    }

}
