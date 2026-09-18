<?php

namespace App\Services;

use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewTrackingService
{
    /*
    |--------------------------------------------------------------------------
    | View Cooldown
    |--------------------------------------------------------------------------
    |
    | Same viewer can generate another view after 24 hours.
    |
    */

    private const VIEW_COOLDOWN_HOURS = 24;

    /*
    |--------------------------------------------------------------------------
    | Track Video View
    |--------------------------------------------------------------------------
    */

    public function track(
        Video $video,
        Request $request
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | Only Count Published Public Videos
        |--------------------------------------------------------------------------
        */

        if (
            $video->status !== 'published' ||
            $video->visibility !== 'public' ||
            !$video->published_at
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Viewer Identification
        |--------------------------------------------------------------------------
        */

        $userId = Auth::id();

        $sessionId = $userId
            ? null
            : $request->session()->getId();

        $ipHash = hash(
            'sha256',
            $request->ip() . config('app.key')
        );

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use ($video, $userId, $sessionId, $ipHash) {

            /*
            |--------------------------------------------------------------------------
            | Find Existing Viewer Record
            |--------------------------------------------------------------------------
            */

            $viewQuery = VideoView::query()
                ->where('video_id', $video->id);

            if ($userId) {
                $viewQuery->where(
                    'user_id',
                    $userId
                );
            } else {
                $viewQuery
                    ->whereNull('user_id')
                    ->where(
                        'session_id',
                        $sessionId
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Existing Record
            |--------------------------------------------------------------------------
            */

            $view = $viewQuery
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Existing View Within Cooldown
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
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Create / Update View Record
            |--------------------------------------------------------------------------
            */

            if (!$view) {
                $view = new VideoView();

                $view->video_id = $video->id;
                $view->user_id = $userId;
                $view->session_id = $sessionId;
                $view->ip_hash = $ipHash;
            } else {
                $view->ip_hash = $ipHash;
            }

            $view->last_viewed_at = now();

            $view->save();

            /*
            |--------------------------------------------------------------------------
            | Increment Video Views
            |--------------------------------------------------------------------------
            */

            Video::query()
                ->whereKey($video->id)
                ->increment('views_count');

            /*
            |--------------------------------------------------------------------------
            | Increment Channel Total Views
            |--------------------------------------------------------------------------
            */

            if ($video->channel_id) {
                DB::table('channels')
                    ->where('id', $video->channel_id)
                    ->increment('total_views');
            }

            return true;
        });
    }
}