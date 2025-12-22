<?php

use App\Http\Controllers\Api\AcademicDataController;
use App\Http\Controllers\Api\adsController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\coursesCotroller;
use App\Http\Controllers\Api\enrollController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\TeachersController;

//Version 1F
Route::prefix('v1')->group(function () {
    // Students register
    Route::prefix('students')->group(function () {
        // Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        // Route::post('/register', [AuthController::class, 'register']);
        Route::post('/store', [AuthController::class, 'store']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/loginSendOtp', [AuthController::class, 'loginSendOtp']);
        Route::middleware(['force.json', 'auth:sanctum'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']); // Logout
            Route::post('/enroll', [enrollController::class, 'enrollCourse']); //enroll
            Route::get('/my-courses', [enrollController::class, 'index']); //enroll
            Route::post('/reviews', [coursesCotroller::class, 'storeReview']); //reviews
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/update-profile-photo', [AuthController::class, 'updateProfilePhoto']);
            Route::get('/top-rated-teachers', [TeachersController::class, 'topRatedTeachers']); //top-rateds-teachers
            Route::post('/course_lessons', [coursesCotroller::class, 'show_course_lessons']); //course_lessons
            Route::get('/teachers-by-cities', [TeachersController::class, 'teachersByCities']); //teachers-by-cities
            Route::post('/join-quiz', [QuizController::class, 'joinQuiz']); //quiz
            Route::post('/close-quiz', [QuizController::class, 'closeQuiz']); //quiz
        });
    }); //End Students
    Route::prefix('quiz')->group(function () {
        Route::get('/index', [QuizController::class, 'index']); //quiz
    });
    // Route::get('/test', function () {
    //     return response()->json(['message' => 'API is working 👌✔️ Welcome to Mindly API!']);
    // });

    //Dosen't need token
    Route::get('/countries', [CountriesController::class, 'index']); //countries
    Route::get('/academic-structure', [AcademicDataController::class, 'getAcademicStructure']); //academic-structure
    Route::get('/courses', [coursesCotroller::class, 'index']); //courses

    Route::get('/ads', [adsController::class, 'index']); //ads

}); //End Version 1
