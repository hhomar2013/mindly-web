<?php

use App\Http\Controllers\PayTabsController;
use App\Http\Controllers\website\HomeController;
use App\Livewire\Admins\Ads\AdsIndex;
use App\Livewire\Admins\Cities\Index as CitiesIndex;
use App\Livewire\Admins\CodeList\CodeListIndex;
use App\Livewire\Admins\ContentTypes\Index as ContentTypesIndex;
use App\Livewire\Admins\Countries\Index as CountriesIndex;
use App\Livewire\Admins\DashboardComponent;
use App\Livewire\Admins\EducationalCenter\Index as EducationalCenter;
use App\Livewire\Admins\EducationalCenter\Wallet\Index as EducationalCenterWalletIndex;
use App\Livewire\Admins\Governorate\Index as GovernorateIndex;
use App\Livewire\Admins\Pdf\CodeList\CodeListPdf;
use App\Livewire\Admins\Quiz\QuizIndex;
use App\Livewire\Admins\SocialMediaTypes\Index as SocialMediaTypesIndex;
use App\Livewire\Admins\Subjects\SubjectIndex;
use App\Livewire\Admins\Teachers\Courses\Index as TeacherCoursesIndex;
use App\Livewire\Admins\Teachers\Index as TeachersIndex;
use App\Livewire\Admins\Teachers\Wallet\Index as TeacherWalletIndex;
use App\Livewire\Admins\TermsConditions\TermsConditionsIndex;
use App\Livewire\Admins\TypeOfSubscription\TosIndex;
use App\Livewire\Admins\Users\UsersIndex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {

        Route::get('/server-time', function () {
            return [
                'now'      => now()->toDateTimeString(),
                'timezone' => config('app.timezone'),
            ];
        });

        Route::get('/clear-all', function () {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('storage:link');

            return "All caches cleared and storage linked!";
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle);
        });

        Route::get('storage-link', function () {
            $target = base_path('storage/app/public');
            $link   = base_path('public_html/storage');
            if (file_exists($link)) {
                return 'Link already exists';
            }
            symlink($target, $link);
            return 'Storage link created successfully!';
        });

        Route::middleware('guest:web')->group(function () {
            Route::get('/', [HomeController::class, 'index'])->name('website.home');
            Route::get('/teacher-profile/{id}', [HomeController::class, 'teacherProfile'])->name('website.teacher.profile');
            Route::get('about-us', function () {
                return view('website.about-us');
            })->name('website.about.us');

            Route::get('/contact-us', function () {
                $settings = new class {use \App\Helpers\GetMainData;};;
                $mainData = $settings->getMainData();
                return view('website.contact-us', ['mainData' => $mainData]);
            })->name('website.contact.us');

            Route::post('/contact-send', [HomeController::class, 'sendEmail'])->name('contact.send');
        });

        Route::middleware('auth:web')->group(function () {
            Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
            Route::get('/users', UsersIndex::class)->name('admins.users.index');                                                             //dashboard
            Route::get('/countries', CountriesIndex::class)->name('admins.countries.index');                                                 //countries
            Route::get('/governorate', GovernorateIndex::class)->name('admins.governorate.index');                                           //govenorate
            Route::get('/cities', CitiesIndex::class)->name('admins.cities.index');                                                          //cities
            Route::get('/educational-center', EducationalCenter::class)->name('admins.educationalCenters.index');                            //eaducational centers
            Route::get('/teachers', TeachersIndex::class)->name('admins.teachers.index');                                                    //Teachers
            Route::get('/social-media-types', SocialMediaTypesIndex::class)->name('admins.socialMediaTypes.index');                          //social media types
            Route::get('/content-types', ContentTypesIndex::class)->name('admins.contentTypes.index');                                       //content types
            Route::get('/subjects', SubjectIndex::class)->name('admins.subjects.index');                                                     //subjects
            Route::get('/educational-center-wallets', EducationalCenterWalletIndex::class)->name('admins.educational-center-wallets.index'); //educational center wallets
            Route::get('/teacher-wallets', TeacherWalletIndex::class)->name('admins.teacher-wallets.index');                                 //teacher wallets
            Route::get('/teacher-courses', TeacherCoursesIndex::class)->name('admins.teacher-courses.index');                                //teacher courses
            Route::get('/type-of-subscriptions', TosIndex::class)->name('admins.tos.index');
            Route::get('/paytabs/checkout', [PayTabsController::class, 'checkout'])->name('paytabs.checkout');
            Route::post('/paytabs/callback', [PayTabsController::class, 'callback'])->name('paytabs.callback');
            Route::get('/paytabs/return', [PayTabsController::class, 'return'])->name('paytabs.return');
            Route::get('/code-list', CodeListIndex::class)->name('admins.code-list.index');
            Route::get('pdf/code-list/{id}', CodeListPdf::class)->name('pdf.code-list');
            Route::get('ads', AdsIndex::class)->name('admins.ads.index');
            Route::get('quiz', QuizIndex::class)->name('admins.quiz.index');
            Route::get('TermsAndCondetions', TermsConditionsIndex::class)->name('admins.termsAndCondetions.index');
            // Route::get('/educational-center-content', ContentIndex::class)->name('admins.educational-center-content.index');
            /*-------------------------------------------------------------------------------------------------------------------------------------------------------*/
            Route::view('profile', 'profile')->middleware(['auth'])->name('profile'); //User Profile
            Route::post('/logout', function (Request $request) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login');
            })->name('logout');
        });
    }
);

require __DIR__ . '/auth.php';
