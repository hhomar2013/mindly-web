<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Override;

class ads extends Model
{
    use Notifiable;
    protected $guarded = [];
    public function adTo()
    {
        return $this->morphTo();
    }


    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

}
