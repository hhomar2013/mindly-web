<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;

class Students extends Model
{
    use HasFactory, HasTranslations, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'parent_phone',
        'email',
        'password',
        'governorate_id',
        'city_id',
        'address',
        'image',
        'date_of_birth',
        'type_of_study',
        'gender',
        'education_id',
        'education_type',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'name' => 'array',
    ];

    public function education()
    {
        return $this->morphTo();
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function hasAcceptedCurrentTerms()
    {
        $currentTerm = TermAndCondition::current();
        if (! $currentTerm) {
            return true;
        }
        // لو مفيش شروط حالياً

        return $this->termAcceptances()
            ->where('term_id', $currentTerm->id)
            ->exists();
    }



    public function termAcceptances()
    {
        return $this->hasMany(TermAcceptance::class);
    }

    public function logs()
    {
        return $this->hasMany(StudentsLogs::class, 'student_id');
    }


    public function is_online()
    {
        return $this->logs()
            ->where('is_active', true)
            ->exists();
    }
}
