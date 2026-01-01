<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class exam_questions extends Model
{
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
    ];
}
