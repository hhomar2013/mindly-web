<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory, HasTranslations;
    public array $translatable = ['name'];
    protected $guarded = [];
    public function governors()
    {
        return $this->hasMany(governor::class);
    }

   
}
