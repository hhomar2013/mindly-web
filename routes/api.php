<?php

use App\Http\Controllers\Api\AcademicDataController;
use App\Http\Controllers\Api\adsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\coursesCotroller;
use App\Http\Controllers\Api\enrollController;
use App\Http\Controllers\Api\MainDataController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\searchController;
use App\Http\Controllers\Api\TeachersController;
use App\Http\Controllers\Api\TermsAndCondetionsController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

//Version 1F
Route::prefix('v1')->group(function () {
    Route::get('/test-lang', function () {
        return response()->json([
            'current_locale' => App::getLocale(),
            'message'        => __('welcome'), // تأكد إن المفتاح ده موجود في ملفات الترجمة
        ]);
    });
    // Students register
    Route::prefix('students')->group(function () {
        // Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        // Route::post('/register', [AuthController::class, 'register']);
        Route::post('/check-email-exists', [AuthController::class, 'checkIfEmailExists']);
        Route::post('/send-another-one-otp', [AuthController::class, 'sendAnotherOneOtp']);
        Route::post('/store', [AuthController::class, 'store']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/login-send-otp', [AuthController::class, 'loginSendOtp']);
        Route::post('/send-new-otp', [AuthController::class, 'sendAnotherOneOtp']);
        Route::middleware(['force.json', 'auth:sanctum'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);         // Logout
            Route::post('/enroll', [enrollController::class, 'enrollCourse']); //enroll
            Route::get('/my-courses', [enrollController::class, 'index']);     //enroll
            Route::post('/reviews', [coursesCotroller::class, 'storeReview']); //reviews
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/update-profile-photo', [AuthController::class, 'updateProfilePhoto']);
            Route::get('/top-rated-teachers', [TeachersController::class, 'topRatedTeachers']); //top-rateds-teachers
            Route::post('/course_lessons', [coursesCotroller::class, 'show_course_lessons']);   //course_lessons
            Route::get('/teachers-by-cities', [TeachersController::class, 'teachersByCities']); //teachers-by-cities
            Route::get('/quiz-instructions/{id}', [QuizController::class, 'instructions']);     //quiz-instructions
            Route::post('/join-quiz', [QuizController::class, 'joinQuiz']);                     //quiz
            Route::post('/close-quiz', [QuizController::class, 'closeQuiz']);                   //quiz
            Route::post('/search', [searchController::class, 'search']);                        //search
            Route::get('/home-page', [TeachersController::class, 'homePage']);                  //home-page
            Route::post('/teacher-profile', [TeachersController::class, 'TeacherProfile']);     //teacher-profile
            Route::post('/center-profile', [TeachersController::class, 'CenterProfile']);       //Center-profile
            Route::post('/change-password', [AuthController::class, 'changePassword']);         //change-password
            Route::post('/delete-account', [AuthController::class, 'deleteAccount']);           //delete-account
        });
    }); //End Students
    Route::prefix('quiz')->group(function () {
        Route::get('/index', [QuizController::class, 'index']); //quiz
    });
    Route::get('/test', function () {
        return response()->json(['message' => 'API is working 👌✔️ Welcome to Mindly API!']);
    });

                                                                                                //Dosen't need token
    Route::get('/countries', [CountriesController::class, 'index']);                            //countries
    Route::get('/academic-structure', [AcademicDataController::class, 'getAcademicStructure']); //academic-structure
    Route::get('/courses', [coursesCotroller::class, 'index']);                                 //courses

    Route::get('/ads', [adsController::class, 'index']); //ads
    Route::get('/terms-and-condetions', [TermsAndCondetionsController::class, 'index']);
    Route::get('/main-data', [MainDataController::class, 'index']); //Main data of platform
});                                                             //End Version 1
