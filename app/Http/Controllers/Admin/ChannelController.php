<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChannelController extends Controller
{
    public function index(
        Request $request
    ): View {
        $search = trim(
            (string) $request->input('search')
        );

        $channels = Channel::query()
            ->with('user')
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'handle',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.channels.index',
            compact('channels', 'search')
        );
    }

    public function destroy(
        Channel $channel
    ): RedirectResponse {
        $channel->load('videos');

        foreach ($channel->videos as $video) {
            if ($video->video_path) {
                Storage::disk('public')->delete(
                    $video->video_path
                );
            }

            if ($video->thumbnail_path) {
                Storage::disk('public')->delete(
                    $video->thumbnail_path
                );
            }
        }

        if ($channel->avatar) {
            Storage::disk('public')->delete(
                $channel->avatar
            );
        }

        if ($channel->banner) {
            Storage::disk('public')->delete(
                $channel->banner
            );
        }

        $channel->videos()->delete();

        $channel->delete();

        return back()->with(
            'success',
            'Channel deleted successfully.'
        );
    }
}