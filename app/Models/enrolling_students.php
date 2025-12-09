<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class enrolling_students extends Model
{
    protected $guarded = [];

    public function code()
    {
        return parent::belongsTo(code_list_body::class, 'code_list_body_id', 'id');
    }
}
