<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class subjects extends Model
{
    use HasTranslations;
    protected $guarded = [];
    protected $translatable = ['name'];
}
