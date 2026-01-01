<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class teacher_wallets extends Model
{
    use HasTranslations, HasFactory;
    protected $guarded  = [];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(teacher_wallet_transactions::class);
    }
}
