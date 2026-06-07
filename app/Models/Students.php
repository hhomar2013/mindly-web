<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FCMNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;

class Students extends Model
{
    use HasApiTokens, HasFactory, HasTranslations, Notifiable;

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
        'status',
        'fcm_token',
    ];

    protected $appends = ['is_online'];

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
        return $value ? asset('storage/'.$value) : null;
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
        return $this->hasMany(StudentsLogs::class, 'student_id', 'id');
    }

    public function getIsOnlineAttribute()
    {
        return $this->logs()
            ->where('is_active', true)
            ->exists();
    }

    public function exams()
    {
        return $this->hasMany(student_exam::class, 'student_id', 'id');
    }

    public static function sendNotification($tokens, $title, $body, $data = [])
    {
        if (empty($tokens)) {
            return 'No tokens found!';
        }

        $messaging = Firebase::messaging();

        $message = CloudMessage::new()
            ->withNotification(FCMNotification::create($title, $body))
            ->withData($data)
            ->withDefaultSounds();
        try {
            $report = $messaging->sendMulticast($message, $tokens);

            return [
                'success' => $report->successes()->count(),
                'failures' => $report->failures()->count(),
            ];
        } catch (\Exception $e) {
            \Log::error('Global Notification Error: '.$e->getMessage());

            return false;
        }
    }
}
