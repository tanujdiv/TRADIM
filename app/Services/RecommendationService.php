<?php

namespace App\Services;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RecommendationService
{
    /*
    |--------------------------------------------------------------------------
    | Recommended Videos
    |--------------------------------------------------------------------------
    */

    public function recommended(int $limit = 12): Collection
    {
        /** @var User|null $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Guest User
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return $this->popular($limit);
        }

        /*
        |--------------------------------------------------------------------------
        | Subscribed Channels
        |--------------------------------------------------------------------------
        */

        $subscribedChannelIds = $user->subscriptions()
            ->pluck('channel_id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Recently Watched Videos
        |--------------------------------------------------------------------------
        */

        $watchedVideoIds = VideoView::query()
            ->where('user_id', $user->id)
            ->latest('id') // updated_at ki jagah id use kiya gaya hai
            ->limit(30)
            ->pluck('video_id')
            ->unique()
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Watched Categories
        |--------------------------------------------------------------------------
        */

        $watchedCategoryIds = [];

        if (!empty($watchedVideoIds)) {
            $watchedCategoryIds = Video::query()
                ->whereIn('id', $watchedVideoIds)
                ->whereNotNull('category_id')
                ->pluck('category_id')
                ->unique()
                ->values()
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | Own Channel
        |--------------------------------------------------------------------------
        */

        $ownChannelId = $user->channel?->id;

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at');

        /*
        |--------------------------------------------------------------------------
        | Don't Recommend Own Videos
        |--------------------------------------------------------------------------
        */

        if ($ownChannelId) {
            $query->where('channel_id', '!=', $ownChannelId);
        }

        /*
        |--------------------------------------------------------------------------
        | Recommendation Score
        |--------------------------------------------------------------------------
        */

        $scoreParts = [];

        /*
        |--------------------------------------------------------------------------
        | Subscribed Channel Score
        |--------------------------------------------------------------------------
        */

        if (!empty($subscribedChannelIds)) {
            $channelIds = implode(',', array_map('intval', $subscribedChannelIds));

            $scoreParts[] = "
                CASE
                    WHEN channel_id IN ($channelIds)
                    THEN 50
                    ELSE 0
                END
            ";
        }

        /*
        |--------------------------------------------------------------------------
        | Watched Category Score
        |--------------------------------------------------------------------------
        */

        if (!empty($watchedCategoryIds)) {
            $categoryIds = implode(',', array_map('intval', $watchedCategoryIds));

            $scoreParts[] = "
                CASE
                    WHEN category_id IN ($categoryIds)
                    THEN 30
                    ELSE 0
                END
            ";
        }

        /*
        |--------------------------------------------------------------------------
        | Views Score
        |--------------------------------------------------------------------------
        */

        $scoreParts[] = "
            LEAST(
                COALESCE(views_count, 0) / 1000,
                20
            )
        ";

        /*
        |--------------------------------------------------------------------------
        | Likes Score
        |--------------------------------------------------------------------------
        */

        $scoreParts[] = "
            LEAST(
                COALESCE(likes_count, 0) * 2,
                20
            )
        ";

        /*
        |--------------------------------------------------------------------------
        | Comments Score
        |--------------------------------------------------------------------------
        */

        $scoreParts[] = "
            LEAST(
                COALESCE(comments_count, 0) * 2,
                10
            )
        ";

        /*
        |--------------------------------------------------------------------------
        | Freshness Score
        |--------------------------------------------------------------------------
        */

        $scoreParts[] = "
            CASE
                WHEN published_at >= NOW() - INTERVAL 1 DAY
                    THEN 20
                WHEN published_at >= NOW() - INTERVAL 7 DAY
                    THEN 12
                WHEN published_at >= NOW() - INTERVAL 30 DAY
                    THEN 5
                ELSE 0
            END
        ";

        $scoreExpression = implode(' + ', $scoreParts);

        /*
        |--------------------------------------------------------------------------
        | Get Recommendations
        |--------------------------------------------------------------------------
        */

        $videos = $query
            ->select('videos.*')
            ->selectRaw("($scoreExpression) as recommendation_score")
            ->orderByDesc('recommendation_score')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        if ($videos->count() < $limit) {
            $existingIds = $videos->pluck('id')->toArray();

            $fallback = $this->popular(
                $limit - $videos->count(),
                $existingIds
            );

            $videos = $videos
                ->concat($fallback)
                ->take($limit)
                ->values();
        }

        return $videos;
    }

    /*
    |--------------------------------------------------------------------------
    | Popular Videos
    |--------------------------------------------------------------------------
    */

    public function popular(
        int $limit = 12,
        array $excludeIds = []
    ): Collection {
        return Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->when(
                !empty($excludeIds),
                function ($query) use ($excludeIds) {
                    $query->whereNotIn('id', $excludeIds);
                }
            )
            ->select('videos.*')
            ->selectRaw('
                (
                    COALESCE(views_count, 0)
                    + (COALESCE(likes_count, 0) * 10)
                    + (COALESCE(comments_count, 0) * 10)
                ) AS popularity_score
            ')
            ->orderByDesc('popularity_score')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Trending Videos
    |--------------------------------------------------------------------------
    */

    public function trending(int $limit = 12): Collection
    {
        return Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->where(
                'published_at',
                '>=',
                now()->subDays(30)
            )
            ->select('videos.*')
            ->selectRaw('
                (
                    COALESCE(views_count, 0)
                    + (COALESCE(likes_count, 0) * 8)
                    + (COALESCE(comments_count, 0) * 10)
                ) AS trending_score
            ')
            ->orderByDesc('trending_score')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Latest Videos
    |--------------------------------------------------------------------------
    */

    public function latest(int $limit = 12): Collection
    {
        return Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Subscription Videos
    |--------------------------------------------------------------------------
    */

    public function subscribed(int $limit = 12): Collection
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        $channelIds = $user->subscriptions()
            ->pluck('channel_id');

        if ($channelIds->isEmpty()) {
            return collect();
        }

        return Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->whereIn('channel_id', $channelIds)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Recently Watched
    |--------------------------------------------------------------------------
    */

    public function recentlyWatched(int $limit = 8): Collection
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        $videoIds = VideoView::query()
            ->where('user_id', $user->id)
            ->latest('id') // updated_at ki jagah id use kiya gaya hai
            ->limit(30)
            ->pluck('video_id')
            ->unique()
            ->values();

        if ($videoIds->isEmpty()) {
            return collect();
        }

        $videos = Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->whereIn('id', $videoIds)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->get();

        return $videoIds
            ->map(function ($id) use ($videos) {
                return $videos->firstWhere('id', $id);
            })
            ->filter()
            ->take($limit)
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Category Videos
    |--------------------------------------------------------------------------
    */

    public function categoryVideos(int $categoryId, int $limit = 8): Collection
    {
        return Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->where('category_id', $categoryId)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}