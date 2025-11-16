<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Teacher extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded         = [];
    public array $translatable = ['name'];

    public function wallet()
    {
        return $this->hasOne(teacher_wallets::class,'teacher_id','id');
    }

    public function city()
    {
        return $this->belongsTo(city::class,'city_id','id');
    }
}
