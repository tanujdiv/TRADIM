<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrapFive();

        RateLimiter::for('tradim-login', function (Request $request) {
            $email = mb_strtolower(
                trim((string) $request->input('email', ''))
            );

            return [
                Limit::perMinute(5)->by(
                    'login-email:' . sha1($email) . ':' . $request->ip()
                ),
                Limit::perMinute(20)->by(
                    'login-ip:' . $request->ip()
                ),
            ];
        });

        RateLimiter::for('tradim-register', function (Request $request) {
            return Limit::perHour(5)
                ->by('register:' . $request->ip());
        });

        RateLimiter::for('tradim-api', function (Request $request) {
            $user = $request->user();

            return $user
                ? Limit::perMinute(120)->by('user:' . $user->id)
                : Limit::perMinute(60)->by('ip:' . $request->ip());
        });

        RateLimiter::for('tradim-upload', function (Request $request) {
            return Limit::perHour(20)
                ->by('upload:' . $request->user()->id);
        });

        RateLimiter::for('tradim-interaction', function (Request $request) {
            return Limit::perMinute(30)
                ->by('interaction:' . $request->user()->id);
        });

        RateLimiter::for('tradim-reports', function (Request $request) {
            return Limit::perHour(10)
                ->by('report-user:' . $request->user()->id);
        });
    }
}