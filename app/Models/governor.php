<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class governor extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded         = [];
    public array $translatable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'governors_id'); // اسم العمود الصحيح في جدول cities
    }
}
