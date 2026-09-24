<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CreatorChannelController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CreatorVideoController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ChannelController as AdminChannelController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/watch/{video:slug}', [VideoController::class, 'show'])->middleware('track.video.view')->name('videos.show');
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

    /*
    |--------------------------------------------------------------------------
     | Channel Management
    |--------------------------------------------------------------------------
      */

    Route::get('/channel/edit', [CreatorChannelController::class, 'edit'])->name('channel.edit');
    Route::put('/channel', [CreatorChannelController::class, 'update'])->name('channel.update');

    /*
    |--------------------------------------------------------------------------
    | Video Management
    |--------------------------------------------------------------------------
    */

    Route::get('/videos', [CreatorVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}/edit', [CreatorVideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [CreatorVideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [CreatorVideoController::class, 'destroy'])->name('videos.destroy');
});


Route::post(
    '/videos/{video}/watch-time',
    [VideoController::class, 'trackWatch']
)->name('videos.watch-time');




if (app()->environment('testing')) {
    Route::middleware([
        'auth',
        'active.user',
    ])->get('/security-test-active', function () {
        return response('OK');
    });
}


/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'active.user', 'admin'])->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/videos', [AdminVideoController::class, 'index'])->name('videos.index');
    Route::delete('/videos/{video}', [AdminVideoController::class, 'destroy'])->name('videos.destroy');

    Route::get('/channels', [AdminChannelController::class, 'index'])->name('channels.index');
    Route::delete('/channels/{channel}', [AdminChannelController::class, 'destroy'])->name('channels.destroy');

    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

});