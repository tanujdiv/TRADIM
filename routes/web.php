<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/


// Register

Route::get(
    '/register',
    [AuthController::class, 'showRegister']
)->name('register');


Route::post(
    '/register',
    [AuthController::class, 'register']
);



/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');


Route::post(
    '/login',
    [AuthController::class, 'login']
);



/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');




/*
|--------------------------------------------------------------------------
| Watch Video
|--------------------------------------------------------------------------
*/

Route::get(
    '/watch/{slug}',
    [VideoController::class, 'show']
)->name('videos.show');



/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Account
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/account',
        function () {
            return view('account');
        }
    )->name('account');


    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/creator',
        [CreatorController::class, 'dashboard']
    )->name('creator.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Create Channel
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/creator/channel/create',
        [CreatorController::class, 'createChannel']
    )->name('creator.channel.create');


    Route::post(
        '/creator/channel',
        [CreatorController::class, 'storeChannel']
    )->name('creator.channel.store');


    /*
    |--------------------------------------------------------------------------
    | Video Upload
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/creator/videos/create',
        [VideoController::class, 'create']
    )->name('videos.create');


    Route::post(
        '/creator/videos',
        [VideoController::class, 'store']
    )->name('videos.store');

    /*
|--------------------------------------------------------------------------
| Video Engagement
|--------------------------------------------------------------------------
*/

    Route::post(
        '/videos/{video}/like',
        [VideoController::class, 'like']
    )->name('videos.like');


    Route::post(
        '/channels/{channel}/subscribe',
        [VideoController::class, 'subscribe']
    )->name('channels.subscribe');


    Route::post(
        '/videos/{video}/comments',
        [CommentController::class, 'store']
    )->name('comments.store');


    Route::delete(
        '/comments/{comment}',
        [CommentController::class, 'destroy']
    )->name('comments.destroy');

});