<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class teacher_secondary_details extends Model
{
    use HasTranslations;
    protected $table = 'teacher_secondary_details';
    protected $guarded = [];
    protected $translatable = ['name'];

    public function secondaryTrack()
    {
        return $this->belongsTo(SecondaryTrack::class);
    }

    public function secondaryGrade()
    {
        return $this->belongsTo(SecondaryGrade::class);
    }

    public function secondaryBranch()
    {
        return $this->belongsTo(SecondaryBranch::class);
    }
    public function secondarySubBranch()
    {
        return $this->belongsTo(SecondarySubBranch::class);
    }

    public function secondarySpecialization()
    {
        return $this->belongsTo(SecondarySpecialization::class);
    }
}
