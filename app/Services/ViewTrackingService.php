<?php

namespace App\Services;

use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViewTrackingService
{
    private const VIEW_COOLDOWN_HOURS = 24;

    public function track(
        Video $video,
        Request $request
    ): bool {
        Log::info('STEP 22: ViewTrackingService START', [
            'video_id' => $video->id,
            'user_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate video
        |--------------------------------------------------------------------------
        */

        if ($video->status !== 'published') {
            Log::warning('STEP 22: View NOT tracked - video not published', [
                'video_id' => $video->id,
                'status' => $video->status,
            ]);

            return false;
        }

        if ($video->visibility !== 'public') {
            Log::warning('STEP 22: View NOT tracked - video not public', [
                'video_id' => $video->id,
                'visibility' => $video->visibility,
            ]);

            return false;
        }

        if (!$video->published_at) {
            Log::warning('STEP 22: View NOT tracked - published_at is NULL', [
                'video_id' => $video->id,
            ]);

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Identify viewer
        |--------------------------------------------------------------------------
        */

        $userId = Auth::id();

        $sessionId = $userId
            ? null
            : $request->session()->getId();

        $ipHash = hash(
            'sha256',
            ($request->ip() ?? '0.0.0.0') . config('app.key')
        );

        Log::info('STEP 22: Viewer identified', [
            'video_id' => $video->id,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_hash' => $ipHash,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Database transaction
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use ($video, $userId, $sessionId, $ipHash) {
            Log::info('STEP 22: Transaction started', [
                'video_id' => $video->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Find existing view
            |--------------------------------------------------------------------------
            */

            $viewQuery = VideoView::query()
                ->where('video_id', $video->id);

            if ($userId) {
                $viewQuery->where(
                    'user_id',
                    $userId
                );

                Log::info('STEP 22: Searching by user_id', [
                    'video_id' => $video->id,
                    'user_id' => $userId,
                ]);
            } else {
                $viewQuery
                    ->whereNull('user_id')
                    ->where(
                        'session_id',
                        $sessionId
                    );

                Log::info('STEP 22: Searching by session_id', [
                    'video_id' => $video->id,
                    'session_id' => $sessionId,
                ]);
            }

            $view = $viewQuery
                ->lockForUpdate()
                ->first();

            Log::info('STEP 22: Existing view lookup result', [
                'found' => (bool) $view,
                'view_id' => $view?->id,
                'last_viewed_at' => $view?->last_viewed_at?->toDateTimeString(),
                'watched_seconds' => $view?->watched_seconds,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 24-hour cooldown
            |--------------------------------------------------------------------------
            */

            if (
                $view &&
                $view->last_viewed_at &&
                $view->last_viewed_at->gt(
                    now()->subHours(
                        self::VIEW_COOLDOWN_HOURS
                    )
                )
            ) {
                Log::info('STEP 22: View blocked by 24-hour cooldown', [
                    'video_id' => $video->id,
                    'view_id' => $view->id,
                    'last_viewed_at' => $view->last_viewed_at->toDateTimeString(),
                ]);

                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Create new view row
            |--------------------------------------------------------------------------
            */

            if (!$view) {
                $view = new VideoView();

                $view->video_id = $video->id;
                $view->user_id = $userId;
                $view->session_id = $sessionId;

                Log::info('STEP 22: Creating NEW VideoView', [
                    'video_id' => $video->id,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Update tracking fields
            |--------------------------------------------------------------------------
            */

            $view->ip_hash = $ipHash;
            $view->last_viewed_at = now();

            Log::info('STEP 22: About to save VideoView', [
                'view_id' => $view->id,
                'video_id' => $view->video_id,
                'user_id' => $view->user_id,
                'session_id' => $view->session_id,
                'last_viewed_at' => $view->last_viewed_at?->toDateTimeString(),
            ]);

            $view->save();

            /*
            |--------------------------------------------------------------------------
            | Re-read row from database
            |--------------------------------------------------------------------------
            */

            $savedView = VideoView::query()
                ->find($view->id);

            Log::info('STEP 22: VideoView AFTER SAVE', [
                'view_id' => $savedView?->id,
                'last_viewed_at' => $savedView?->last_viewed_at?->toDateTimeString(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Increment video views
            |--------------------------------------------------------------------------
            */

            Video::query()
                ->whereKey($video->id)
                ->increment('views_count');

            Log::info('STEP 22: Video views_count incremented', [
                'video_id' => $video->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Increment channel views
            |--------------------------------------------------------------------------
            */

            if ($video->channel_id) {
                DB::table('channels')
                    ->where('id', $video->channel_id)
                    ->increment('total_views');

                Log::info('STEP 22: Channel total_views incremented', [
                    'channel_id' => $video->channel_id,
                ]);
            }

            Log::info('STEP 22: View TRACKED successfully', [
                'video_id' => $video->id,
                'view_id' => $view->id,
            ]);

            return true;
        });
    }
}