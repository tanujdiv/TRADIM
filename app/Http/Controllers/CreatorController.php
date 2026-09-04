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
    public function dashboard()
    {
        $user = Auth::user();
        $channel = $user->channel;

        if (!$channel) {
            return redirect()->route('creator.channel.create');
        }

        $channelVideoIds = $channel->videos()->pluck('id');

        $totalVideos = $channelVideoIds->count();
        $totalViews = (int) $channel->videos()->sum('views_count');
        $totalLikes = (int) $channel->videos()->sum('likes_count');
        $totalComments = (int) $channel->videos()->sum('comments_count');
        $totalSubscribers = (int) $channel->subscriber_count;

        // Channel level total seconds directly from video_views table
        $totalWatchedSeconds = (int) VideoView::whereIn('video_id', $channelVideoIds)->sum('watched_seconds');
        $totalWatchHours = round($totalWatchedSeconds / 3600, 2);

        // Videos list with guaranteed subquery for total_watched_seconds
        $videos = $channel->videos()
            ->with('category')
            ->select('videos.*')
            ->selectSub(function ($query) {
                $query->from('video_views')
                    ->selectRaw('COALESCE(SUM(watched_seconds), 0)')
                    ->whereColumn('video_views.video_id', 'videos.id');
            }, 'total_watched_seconds')
            ->latest()
            ->paginate(10);

        return view('creator.dashboard', compact(
            'channel',
            'videos',
            'totalVideos',
            'totalViews',
            'totalLikes',
            'totalComments',
            'totalSubscribers',
            'totalWatchedSeconds',
            'totalWatchHours'
        ));
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
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'handle' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:channels,handle'],
            'description' => ['nullable', 'string', 'max:1000'],
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
            ->with('success', 'Your Tradim channel has been created!');
    }
}