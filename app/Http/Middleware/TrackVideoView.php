<?php

namespace App\Http\Middleware;

use App\Models\Video;
use App\Services\ViewTrackingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVideoView
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | Get Route Video
        |--------------------------------------------------------------------------
        */

        $video = $request->route('slug');

        if (!$video instanceof Video) {
            return $response;
        }

        /*
        |--------------------------------------------------------------------------
        | Track View
        |--------------------------------------------------------------------------
        */

        app(ViewTrackingService::class)
            ->track(
                $video,
                $request
            );

        return $response;
    }
}