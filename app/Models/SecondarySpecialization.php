<?php
// App/Models/SecondarySpecialization.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SecondarySpecialization extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];
    protected $casts = [
        'name' => 'array',
    ];

    public $translatable = ['name'];

    public function track()
    {
        return $this->belongsTo(SecondaryTrack::class, 'secondary_track_id');
    }
}
