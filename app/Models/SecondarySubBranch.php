<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SecondarySubBranch extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    public $translatable = ['name'];
    
    public function branch()
    {
        return $this->belongsTo(SecondaryBranch::class, 'secondary_branch_id');
    }
}
