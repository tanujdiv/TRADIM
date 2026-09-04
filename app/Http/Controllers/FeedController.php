<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Subscription Feed
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $channelIds = $user->subscriptions()
            ->pluck('channel_id');

        $videos = Video::with([
                'channel',
                'category',
            ])
            ->whereIn('channel_id', $channelIds)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(12);

        return view('feed.index', compact('videos'));
    }

    /*
    |--------------------------------------------------------------------------
    | Subscribed Channels
    |--------------------------------------------------------------------------
    */

    public function channels()
    {
        // Auth check fallback if middleware isn't present
        abort_if(!Auth::check(), 401);

        /** @var User $user */
        $user = Auth::user();

        $channelIds = $user->subscriptions()
            ->pluck('channel_id');

        $channels = Channel::with('user')
            ->whereIn('id', $channelIds)
            ->latest()
            ->paginate(12);

        return view('feed.channels', compact('channels'));
    }
}