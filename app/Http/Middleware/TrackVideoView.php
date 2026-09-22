<?php

namespace App\Http\Middleware;

use App\Models\Video;
use App\Services\ViewTrackingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackVideoView
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        Log::info('STEP 22: TrackVideoView middleware START', [
            'url' => $request->fullUrl(),
            'route_name' => $request->route()?->getName(),
            'route_video' => $request->route('video'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Let controller render the page first
        |--------------------------------------------------------------------------
        */

        $response = $next($request);

        Log::info('STEP 22: Controller response received', [
            'status' => $response->getStatusCode(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get video slug from route
        |--------------------------------------------------------------------------
        */

        $videoSlug = $request->route('video');

        if (!is_string($videoSlug)) {
            Log::error('STEP 22: Unexpected route video parameter', [
                'type' => gettype($videoSlug),
                'value' => $videoSlug,
            ]);

            return $response;
        }

        /*
        |--------------------------------------------------------------------------
        | Find video using slug
        |--------------------------------------------------------------------------
        */

        $video = Video::query()
            ->where('slug', $videoSlug)
            ->first();

        if (!$video) {
            Log::error('STEP 22: Video not found for view tracking', [
                'slug' => $videoSlug,
            ]);

            return $response;
        }

        Log::info('STEP 22: Video found for tracking', [
            'video_id' => $video->id,
            'slug' => $video->slug,
            'status' => $video->status,
            'visibility' => $video->visibility,
            'published_at' => $video->published_at,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Track view
        |--------------------------------------------------------------------------
        */

        try {
            $tracked = app(ViewTrackingService::class)->track(
                $video,
                $request
            );

            Log::info('STEP 22: ViewTrackingService completed', [
                'video_id' => $video->id,
                'tracked' => $tracked,
            ]);
        } catch (\Throwable $e) {
            Log::error('STEP 22: ViewTrackingService FAILED', [
                'video_id' => $video->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        Log::info('STEP 22: TrackVideoView middleware END');

        return $response;
    }
}