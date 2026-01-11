<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ads extends Model
{
    protected $guarded = [];
    public function adTo()
    {
        return $this->morphTo();
    }

}
