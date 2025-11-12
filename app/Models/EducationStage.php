<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EducationStage extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];
    protected $translatable = ['name'];
    public function system()
    {
        return $this->belongsTo(EducationSystem::class, 'education_system_id');
    }

    public function grades()
    {
        return $this->hasMany(StageGrade::class);
    }

    public function tracks()
    {
        return $this->hasMany(SecondaryTrack::class);
    }



    public function faculties()
    {

        return $this->hasMany(UniversityFaculty::class, 'education_stage_id');
    }

    public function institutes()
    {
        return $this->hasMany(UniversityInstitute::class, 'education_stage_id');
    }

    public function universityAcademicYears()
    {
        return $this->hasMany(UniversityAcademicYear::class, 'education_stage_id');
    }
}
