<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class exam extends Model
{
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(exam_questions::class, 'exam_id', 'id');
    }
}
