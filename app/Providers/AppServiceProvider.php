<?php

namespace App\Providers;

use App\Helpers\GetMainData;
use App\Models\Center;
use App\Models\secondary_student_details;
use App\Models\StageGrade;
use App\Models\Teacher;
use App\Models\TeacherCourseOverview;
use App\Models\teacher_secondary_details;
use App\Models\universty_student_details;
use App\Observers\TeacherCourseOverviewObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Schema::defaultStringLength(191);
        TeacherCourseOverview::observe(TeacherCourseOverviewObserver::class);
        Relation::morphMap([
            'stage_grades'               => StageGrade::class,
            'secondary'                  => secondary_student_details::class,
            'secondary_teacher_details'  => teacher_secondary_details::class,
            'university_student_details' => universty_student_details::class,
            'ads_teacher'                => Teacher::class,
            'ads_center'                 => Center::class,
            'ads_course'                 => TeacherCourseOverview::class,
        ]);

        // if (env('APP_ENV') === 'production') {
        //     URL::forceScheme('https');
        // }

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->setTimezone(config('app.timezone'))->toDateTimeString();
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/custom/livewire/update', $handle);
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::prefix(LaravelLocalization::setLocale())
                ->post('livewire/update', $handle);
            // ->middleware('livewire');
        });

        View::composer('layouts.landing', function ($view) {
            // إنشاء كلاس سريع لاستخدام الـ Trait
            $helper = new class {
                use GetMainData;
            };
            $view->with('mainData', $helper->getMainData());
        });
    }
}
