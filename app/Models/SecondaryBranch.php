<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SecondaryBranch extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];

    public function track()
    {
        return $this->belongsTo(SecondaryTrack::class, 'secondary_track_id');
    }

    public function subBranches()
    {
        return $this->hasMany(SecondarySubBranch::class, 'secondary_branch_id');
    }
}
