<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class UniversityInstitute extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];
    public function system()
    {
        return $this->belongsTo(EducationSystem::class, 'education_system_id');
    }
}
