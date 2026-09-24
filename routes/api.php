<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChannelController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tradim API V1
|--------------------------------------------------------------------------
| All API routes are versioned under /api/v1.
*/


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('v1/auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('api.v1.auth.register')->middleware('throttle:tradim-register');

    Route::post('/login', [AuthController::class, 'login'])->name('api.v1.auth.login')->middleware('throttle:tradim-login');

});


/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('throttle:tradim-api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    Route::get('/videos', [VideoController::class, 'index'])->name('api.v1.videos.index');

    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('api.v1.videos.show');


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index'])->name('api.v1.categories.index');

    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('api.v1.categories.show');


    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    */

    Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('api.v1.channels.show');


    /*
    |--------------------------------------------------------------------------
    | Public Comments
    |--------------------------------------------------------------------------
    */

    Route::get('/videos/{video}/comments', [CommentController::class, 'index'])->name('api.v1.videos.comments.index');

});


/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware(['auth:sanctum', 'active.user', 'throttle:tradim-api'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.v1.auth.logout');

    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll'])->name('api.v1.auth.logout-all');

    Route::get('/auth/me', [AuthController::class, 'me'])->name('api.v1.auth.me');


    /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */

    Route::post('/channels/{channel}/subscribe', [SubscriptionController::class, 'store'])->middleware('throttle:tradim-interaction')->name('api.v1.channels.subscribe');

    Route::delete('/channels/{channel}/subscribe', [SubscriptionController::class, 'destroy'])->middleware('throttle:tradim-interaction')->name('api.v1.channels.unsubscribe');


    /*
    |--------------------------------------------------------------------------
    | Likes / Dislikes
    |--------------------------------------------------------------------------
    */

    Route::post('/videos/{video}/like', [LikeController::class, 'store'])->middleware('throttle:tradim-interaction')->name('api.v1.videos.like');

    Route::delete('/videos/{video}/like', [LikeController::class, 'destroy'])->middleware('throttle:tradim-interaction')->name('api.v1.videos.unlike');


    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */

    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->middleware('throttle:tradim-interaction')->name('api.v1.videos.comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('throttle:tradim-interaction')->name('api.v1.comments.destroy');


    /*
    |--------------------------------------------------------------------------
    | Reporting System - Step 28
    |--------------------------------------------------------------------------
    */

    Route::get('/reports', [ReportController::class, 'index'])->name('api.v1.reports.index');

    Route::post('/reports', [ReportController::class, 'store'])->middleware('throttle:tradim-reports')->name('api.v1.reports.store');

});