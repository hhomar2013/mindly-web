<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TermAndCondition extends Model
{
    use HasTranslations;

    protected $guarded         = [];
    public array $translatable = ['content'];
    protected $table           = 'terms_and_conditions';
    public static function current($type = 'terms')
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->latest()
            ->first();
    }
}
