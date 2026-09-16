<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\VideoView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatorController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $channel = $user->channel;

        if (!$channel) {
            return redirect()->route('creator.channel.create');
        }

        /*
        |--------------------------------------------------------------------------
        | Analytics Filter
        |--------------------------------------------------------------------------
        */

        $period = $request->get('period', 'daily');

        if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
            $period = 'daily';
        }

        /*
        |--------------------------------------------------------------------------
        | Channel Videos
        |--------------------------------------------------------------------------
        */

        $channelVideoIds = $channel->videos()->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Lifetime Statistics
        |--------------------------------------------------------------------------
        */

        $totalVideos = $channelVideoIds->count();

        $totalViews = (int) $channel->videos()
            ->sum('views_count');

        $totalLikes = (int) $channel->videos()
            ->sum('likes_count');

        $totalComments = (int) $channel->videos()
            ->sum('comments_count');

        $totalSubscribers = (int) $channel->subscriber_count;

        /*
        |--------------------------------------------------------------------------
        | Total Watch Time
        |--------------------------------------------------------------------------
        | Reusing existing Step 15 watch-time data.
        */

        $totalWatchedSeconds = (int) VideoView::whereIn(
            'video_id',
            $channelVideoIds
        )->sum('watched_seconds');

        $totalWatchHours = round(
            $totalWatchedSeconds / 3600,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Videos List
        |--------------------------------------------------------------------------
        */

        $videos = $channel->videos()
            ->with('category')
            ->select('videos.*')
            ->selectSub(function ($query) {
                $query->from('video_views')
                    ->selectRaw(
                        'COALESCE(SUM(watched_seconds), 0)'
                    )
                    ->whereColumn(
                        'video_views.video_id',
                        'videos.id'
                    );
            }, 'total_watched_seconds')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Analytics Date Range
        |--------------------------------------------------------------------------
        */

        if ($period === 'daily') {
            // Last 7 days
            $startDate = now()
                ->copy()
                ->subDays(6)
                ->startOfDay();

            $endDate = now()
                ->copy()
                ->endOfDay();

        } elseif ($period === 'weekly') {
            // Last 8 weeks
            $startDate = now()
                ->copy()
                ->subWeeks(7)
                ->startOfWeek();

            $endDate = now()
                ->copy()
                ->endOfWeek();

        } else {
            // Last 12 months
            $startDate = now()
                ->copy()
                ->subMonths(11)
                ->startOfMonth();

            $endDate = now()
                ->copy()
                ->endOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | Analytics Labels
        |--------------------------------------------------------------------------
        */

        $analyticsLabels = [];

        $cursor = $startDate->copy();

        if ($period === 'daily') {

            while ($cursor <= $endDate) {
                $analyticsLabels[] = $cursor->format('d M');
                $cursor->addDay();
            }

        } elseif ($period === 'weekly') {

            while ($cursor <= $endDate) {
                $analyticsLabels[] = $cursor->format('d M');
                $cursor->addWeek();
            }

        } else {

            while ($cursor <= $endDate) {
                $analyticsLabels[] = $cursor->format('M Y');
                $cursor->addMonth();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW ANALYTICS
        |--------------------------------------------------------------------------
        | Using video_views.created_at because historical views_count
        | snapshots are not stored yet.
        */

        $viewRows = VideoView::query()
            ->whereIn('video_id', $channelVideoIds)
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                DB::raw(
                    $this->analyticsGroupExpression(
                        'created_at',
                        $period
                    ) . ' as analytics_period'
                ),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('analytics_period')
            ->orderBy('analytics_period')
            ->pluck('total', 'analytics_period');

        /*
        |--------------------------------------------------------------------------
        | WATCH TIME ANALYTICS
        |--------------------------------------------------------------------------
        | Reusing Step 15 watched_seconds.
        */

        $watchRows = VideoView::query()
            ->whereIn('video_id', $channelVideoIds)
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                DB::raw(
                    $this->analyticsGroupExpression(
                        'created_at',
                        $period
                    ) . ' as analytics_period'
                ),
                DB::raw(
                    'COALESCE(SUM(watched_seconds), 0) as total'
                )
            )
            ->groupBy('analytics_period')
            ->orderBy('analytics_period')
            ->pluck('total', 'analytics_period');

        /*
        |--------------------------------------------------------------------------
        | SUBSCRIBER GROWTH
        |--------------------------------------------------------------------------
        */

        $subscriberRows = DB::table('subscriptions')
            ->where('channel_id', $channel->id)
            ->whereBetween('created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                DB::raw(
                    $this->analyticsGroupExpression(
                        'created_at',
                        $period
                    ) . ' as analytics_period'
                ),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('analytics_period')
            ->orderBy('analytics_period')
            ->pluck('total', 'analytics_period');

        /*
        |--------------------------------------------------------------------------
        | LIKES ANALYTICS
        |--------------------------------------------------------------------------
        */

        $likeRows = DB::table('likes')
            ->join(
                'videos',
                'videos.id',
                '=',
                'likes.video_id'
            )
            ->where('videos.channel_id', $channel->id)
            ->where('likes.type', 'like')
            ->whereBetween('likes.created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                DB::raw(
                    $this->analyticsGroupExpression(
                        'likes.created_at',
                        $period
                    ) . ' as analytics_period'
                ),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('analytics_period')
            ->orderBy('analytics_period')
            ->pluck('total', 'analytics_period');

        /*
        |--------------------------------------------------------------------------
        | COMMENTS ANALYTICS
        |--------------------------------------------------------------------------
        */

        $commentRows = DB::table('comments')
            ->join(
                'videos',
                'videos.id',
                '=',
                'comments.video_id'
            )
            ->where('videos.channel_id', $channel->id)
            ->whereBetween('comments.created_at', [
                $startDate,
                $endDate
            ])
            ->select(
                DB::raw(
                    $this->analyticsGroupExpression(
                        'comments.created_at',
                        $period
                    ) . ' as analytics_period'
                ),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('analytics_period')
            ->orderBy('analytics_period')
            ->pluck('total', 'analytics_period');

        /*
        |--------------------------------------------------------------------------
        | Build Chart Data With Zero Values
        |--------------------------------------------------------------------------
        */

        $viewsData = [];
        $watchTimeData = [];
        $subscriberData = [];
        $likesData = [];
        $commentsData = [];

        $cursor = $startDate->copy();

        while ($cursor <= $endDate) {

            if ($period === 'daily') {

                $key = $cursor->format('Y-m-d');
                $cursor->addDay();

            } elseif ($period === 'weekly') {

                $key = $cursor->format('Y-m-d');
                $cursor->addWeek();

            } else {

                $key = $cursor->format('Y-m');
                $cursor->addMonth();
            }

            $viewsData[] = (int) ($viewRows[$key] ?? 0);

            $watchTimeData[] = round(
                ((int) ($watchRows[$key] ?? 0)) / 60,
                2
            );

            $subscriberData[] = (int) (
                $subscriberRows[$key] ?? 0
            );

            $likesData[] = (int) ($likeRows[$key] ?? 0);

            $commentsData[] = (int) (
                $commentRows[$key] ?? 0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOP VIDEOS
        |--------------------------------------------------------------------------
        */

        $topVideos = $channel->videos()
            ->select([
                'id',
                'title',
                'thumbnail_path',
                'views_count',
                'likes_count',
                'comments_count',
            ])
            ->orderByDesc('views_count')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view('creator.dashboard', compact(
            'channel',
            'videos',
            'totalVideos',
            'totalViews',
            'totalLikes',
            'totalComments',
            'totalSubscribers',
            'totalWatchedSeconds',
            'totalWatchHours',
            'period',
            'analyticsLabels',
            'viewsData',
            'watchTimeData',
            'subscriberData',
            'likesData',
            'commentsData',
            'topVideos'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Analytics Group Expression
    |--------------------------------------------------------------------------
    */

    private function analyticsGroupExpression(
        string $column,
        string $period
    ): string {
        if ($period === 'daily') {
            return "DATE($column)";
        }

        if ($period === 'weekly') {
            return "DATE_SUB(DATE($column), INTERVAL WEEKDAY($column) DAY)";
        }

        return "DATE_FORMAT($column, '%Y-%m')";
    }

    public function createChannel()
    {
        $user = Auth::user();

        if ($user->channel) {
            return redirect()->route('creator.dashboard');
        }

        return view('creator.channel.create');
    }

    public function storeChannel(Request $request)
    {
        $user = Auth::user();

        if ($user->channel) {
            return redirect()->route('creator.dashboard');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],

            'handle' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                'unique:channels,handle'
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $counter = 1;

        while (Channel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Channel::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'handle' => strtolower($validated['handle']),
            'description' => $validated['description'] ?? null,
            'subscriber_count' => 0,
            'video_count' => 0,
            'total_views' => 0,
            'is_verified' => false,
            'is_active' => true,
        ]);

        return redirect()
            ->route('creator.dashboard')
            ->with(
                'success',
                'Your Tradim channel has been created!'
            );
    }
}