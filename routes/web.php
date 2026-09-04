<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CreatorVideoController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/watch/{slug}', [VideoController::class, 'show'])->name('videos.show');
Route::get('/channel/{handle}', [ChannelController::class, 'show'])->name('channels.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/account', function () {
        return view('account');
    })->name('account');

    // Video Engagement
    Route::post('/videos/{video}/like', [VideoController::class, 'like'])->name('videos.like');
    Route::post('/channels/{channel}/subscribe', [VideoController::class, 'subscribe'])->name('channels.subscribe');
    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Channel Creation
    Route::get('/creator/channel/create', [CreatorController::class, 'createChannel'])->name('creator.channel.create');
    Route::post('/creator/channel', [CreatorController::class, 'storeChannel'])->name('creator.channel.store');

    // Video Creation
    Route::get('/creator/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/creator/videos', [VideoController::class, 'store'])->name('videos.store');

    //Track Watch Time
    Route::post('/videos/{video}/watch-time', [VideoController::class, 'trackWatch'])->name('videos.watch-time');

    //Subscription Feed

    Route::get('/subscriptions', [FeedController::class, 'index'])->name('feed.index');
    Route::get('/subscriptions/channels', [FeedController::class, 'channels'])->name('feed.channels');
});
/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');

    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});

/*
|--------------------------------------------------------------------------
| Creator Studio Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('creator')->name('creator.')->group(function () {
    Route::get('/', [CreatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/videos', [CreatorVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}/edit', [CreatorVideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [CreatorVideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [CreatorVideoController::class, 'destroy'])->name('videos.destroy');
});