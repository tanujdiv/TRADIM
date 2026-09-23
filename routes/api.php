<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChannelController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tradim API V1
|--------------------------------------------------------------------------
|
| All API routes are versioned under /api/v1.
|
*/


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('v1/auth')->group(function () {

    Route::post(
        '/register',
        [AuthController::class, 'register']
    )->name('api.v1.auth.register');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    )->name('api.v1.auth.login');

});


/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/videos',
        [VideoController::class, 'index']
    )->name('api.v1.videos.index');

    Route::get(
        '/videos/{video}',
        [VideoController::class, 'show']
    )->name('api.v1.videos.show');


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    )->name('api.v1.categories.index');

    Route::get(
        '/categories/{category}',
        [CategoryController::class, 'show']
    )->name('api.v1.categories.show');


    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/channels/{channel}',
        [ChannelController::class, 'show']
    )->name('api.v1.channels.show');

});


/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/auth/logout',
            [AuthController::class, 'logout']
        )->name('api.v1.auth.logout');

        Route::get(
            '/auth/me',
            [AuthController::class, 'me']
        )->name('api.v1.auth.me');


        /*
        |--------------------------------------------------------------------------
        | Subscriptions
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/channels/{channel}/subscribe',
            [SubscriptionController::class, 'store']
        )->name('api.v1.channels.subscribe');

        Route::delete(
            '/channels/{channel}/subscribe',
            [SubscriptionController::class, 'destroy']
        )->name('api.v1.channels.unsubscribe');


        /*
        |--------------------------------------------------------------------------
        | Likes / Dislikes
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/videos/{video}/like',
            [LikeController::class, 'store']
        )->name('api.v1.videos.like');

        Route::delete(
            '/videos/{video}/like',
            [LikeController::class, 'destroy']
        )->name('api.v1.videos.unlike');


        /*
        |--------------------------------------------------------------------------
        | Comments
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/videos/{video}/comments',
            [CommentController::class, 'index']
        )->withoutMiddleware('auth:sanctum')
            ->name('api.v1.videos.comments.index');

        Route::post(
            '/videos/{video}/comments',
            [CommentController::class, 'store']
        )->name('api.v1.videos.comments.store');

        Route::delete(
            '/comments/{comment}',
            [CommentController::class, 'destroy']
        )->name('api.v1.comments.destroy');

    });