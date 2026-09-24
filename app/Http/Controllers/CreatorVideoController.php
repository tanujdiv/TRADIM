<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CreatorVideoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Creator Videos
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Create your channel first.');
        }

        Gate::authorize('view', $channel);

        $videos = Video::with('category')
            ->where('channel_id', $channel->id)
            ->latest()
            ->paginate(12);

        return view(
            'creator.videos.index',
            compact('channel', 'videos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Video
    |--------------------------------------------------------------------------
    */

    public function edit(Video $video)
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Create your channel first.');
        }

        Gate::authorize('update', $video);

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view(
            'creator.videos.edit',
            compact(
                'video',
                'channel',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Video
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Video $video
    ) {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Create your channel first.');
        }

        Gate::authorize('update', $video);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'visibility' => [
                'required',
                'in:public,unlisted,private',
            ],

            'status' => [
                'required',
                'in:draft,published',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Thumbnail
        |--------------------------------------------------------------------------
        */

        $thumbnailPath = $video->thumbnail_path;

        if ($request->hasFile('thumbnail')) {

            $newThumbnailPath = $request
                ->file('thumbnail')
                ->store('thumbnails', 'public');

            if (
                $thumbnailPath &&
                Storage::disk('public')->exists($thumbnailPath)
            ) {
                Storage::disk('public')->delete(
                    $thumbnailPath
                );
            }

            $thumbnailPath = $newThumbnailPath;
        }

        /*
        |--------------------------------------------------------------------------
        | Published At
        |--------------------------------------------------------------------------
        */

        $publishedAt = $video->published_at;

        if ($validated['status'] === 'published') {

            if (!$publishedAt) {
                $publishedAt = now();
            }

        } else {

            $publishedAt = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Video
        |--------------------------------------------------------------------------
        */

        $video->update([

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'category_id' =>
                $validated['category_id'] ?? null,

            'thumbnail_path' =>
                $thumbnailPath,

            'visibility' =>
                $validated['visibility'],

            'status' =>
                $validated['status'],

            'published_at' =>
                $publishedAt,

        ]);

        return redirect()
            ->route('creator.videos.index')
            ->with(
                'success',
                'Video updated successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Video
    |--------------------------------------------------------------------------
    */

    public function destroy(Video $video)
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Create your channel first.');
        }

        Gate::authorize('delete', $video);

        /*
        |--------------------------------------------------------------------------
        | Delete Video File
        |--------------------------------------------------------------------------
        */

        if (
            $video->video_path &&
            Storage::disk('public')->exists(
                $video->video_path
            )
        ) {
            Storage::disk('public')->delete(
                $video->video_path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Thumbnail
        |--------------------------------------------------------------------------
        */

        if (
            $video->thumbnail_path &&
            Storage::disk('public')->exists(
                $video->thumbnail_path
            )
        ) {
            Storage::disk('public')->delete(
                $video->thumbnail_path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Video
        |--------------------------------------------------------------------------
        */

        $video->delete();

        /*
        |--------------------------------------------------------------------------
        | Update Channel Count
        |--------------------------------------------------------------------------
        */

        if ($channel->video_count > 0) {
            $channel->decrement('video_count');
        }

        return redirect()
            ->route('creator.videos.index')
            ->with(
                'success',
                'Video deleted successfully!'
            );
    }
}