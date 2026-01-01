<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SecondaryTrack extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];

    public function stage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }

    public function branches()
    {
        return $this->hasMany(SecondaryBranch::class);
    }



    public function grades()
    {
        // نستخدم hasMany لربط SecondaryTrack بـ SecondaryGrade
        return $this->hasMany(SecondaryGrade::class, 'secondary_track_id');
    }


    /**
     * العلاقة: المسار له تخصصات (مثل البكالوريا الفنية أو المصرية)
     */
    public function specializations()
    {
        return $this->hasMany(SecondarySpecialization::class, 'secondary_track_id');
    }
}
