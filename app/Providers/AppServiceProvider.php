<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\secondary_student_details;
use App\Models\StageGrade;
use App\Models\teacher_secondary_details;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'stage_grades' => StageGrade::class,
            'secondary'    => secondary_student_details::class,
            'secondary_teacher_details' => teacher_secondary_details::class,
        ]);

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Carbon::serializeUsing(function ($carbon) {
            return $carbon->setTimezone(config('app.timezone'))->toDateTimeString();
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::prefix(LaravelLocalization::setLocale())
                ->post('livewire/update', $handle)
                ->middleware('livewire');
        });
    }
}
