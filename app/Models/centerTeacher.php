<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class centerTeacher extends Model
{
    protected $guarded = [];    
    // public $table = 'center_teachers';
    // public $timestamps = true;
    // protected $fillable = [
    //     'center_id',
    //     'teacher_id',
    // ];

    public function centers()
    {
        return $this->belongsTo(Center::class,'center_id');
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
}
