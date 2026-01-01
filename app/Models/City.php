<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations;
    public array $translatable = ['name'];
    protected $guarded  =  [];

    public function Governorates()
    {
        return $this->belongsTo(governor::class, 'governors_id');
    }
}
