<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Center extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded         = [];
    public array $translatable = ['name'];

    public function wallet()
    {
        return $this->hasOne(center_wallets::class);
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function centerTeachers()
    {
        return $this->hasMany(centerTeacher::class, 'center_id', 'id');
    }

}
