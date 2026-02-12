<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermAcceptance extends Model
{
    protected $guarded = [];
    protected $table   = 'term_acceptances';
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function term()
    {
        return $this->belongsTo(TermAndCondition::class);
    }
}
