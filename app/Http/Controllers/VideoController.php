<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class VideoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Upload Video Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with(
                    'error',
                    'Create your channel first.'
                );
        }


        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('sort_order')
            ->get();


        return view(
            'videos.create',
            compact(
                'channel',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Video
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        $channel = $user->channel;


        if (!$channel) {

            return redirect()
                ->route('creator.channel.create')
                ->with(
                    'error',
                    'Create your channel first.'
                );
        }


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

            'video' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo',
                'max:102400',
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

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug(
            $validated['title']
        );

        $originalSlug = $slug;

        $counter = 1;


        while (
            Video::where(
                'slug',
                $slug
            )->exists()
        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | Store Video
        |--------------------------------------------------------------------------
        */

        $videoPath = $request
            ->file('video')
            ->store(
                'videos',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Store Thumbnail
        |--------------------------------------------------------------------------
        */

        $thumbnailPath = null;


        if ($request->hasFile('thumbnail')) {

            $thumbnailPath = $request
                ->file('thumbnail')
                ->store(
                    'thumbnails',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Video Status
        |--------------------------------------------------------------------------
        */

        $status = 'published';

        $publishedAt = now();


        if (
            $validated['visibility'] !== 'public'
        ) {

            $status = 'published';
        }


        /*
        |--------------------------------------------------------------------------
        | Create Video
        |--------------------------------------------------------------------------
        */

        $video = Video::create([

            'user_id' => $user->id,

            'channel_id' => $channel->id,

            'category_id' =>
                $validated['category_id'] ?? null,

            'title' =>
                $validated['title'],

            'slug' =>
                $slug,

            'description' =>
                $validated['description'] ?? null,

            'video_path' =>
                $videoPath,

            'thumbnail_path' =>
                $thumbnailPath,

            'duration' => 0,

            'visibility' =>
                $validated['visibility'],

            'status' =>
                $status,

            'views_count' => 0,

            'likes_count' => 0,

            'dislikes_count' => 0,

            'comments_count' => 0,

            'published_at' =>
                $publishedAt,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Channel Counters
        |--------------------------------------------------------------------------
        */

        $channel->increment(
            'video_count'
        );


        return redirect()
            ->route('creator.dashboard')
            ->with(
                'success',
                'Video uploaded successfully!'
            );
    }
}