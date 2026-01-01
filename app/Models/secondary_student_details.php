<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class secondary_student_details extends Model
{

    use HasFactory;
    protected $guarded = [];
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
