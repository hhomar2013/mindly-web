<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermAndCondition extends Model
{
    protected $guarded = [];
    protected $table   = 'terms_and_conditions';
    public static function current($type = 'terms')
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->latest()
            ->first();
    }
}
