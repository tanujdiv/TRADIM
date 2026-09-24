<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(
        Request $request
    ): View {
        $search = trim(
            (string) $request->input('search')
        );

        $videos = Video::query()
            ->with([
                'channel',
                'category',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'title',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'slug',
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
            'admin.videos.index',
            compact('videos', 'search')
        );
    }

    public function destroy(
        Video $video
    ): RedirectResponse {
        $channel = $video->channel;

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

        $video->delete();

        if ($channel) {
            $channel->update([
                'video_count' => max(
                    0,
                    (int) $channel->video_count - 1
                ),
            ]);
        }

        return back()->with(
            'success',
            'Video deleted successfully.'
        );
    }
}