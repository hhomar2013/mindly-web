<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class StageGrade extends Model
{
    use HasFactory,HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];
    public function stage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }

}
