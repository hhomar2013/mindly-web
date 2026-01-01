<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EducationSystem extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];

    public function stages()
    {
        return $this->hasMany(EducationStage::class);
    }

    public function faculties()
    {
        return $this->hasMany(UniversityFaculty::class);
    }

    public function institutes()
    {
        return $this->hasMany(UniversityInstitute::class);
    }
}
