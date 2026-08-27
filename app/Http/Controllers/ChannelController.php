<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Channel Page
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = Auth::user();

        // User already has channel
        if ($user->channel) {
            return redirect()
                ->route('channels.show', $user->channel->handle);
        }

        return view('creator.channel.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Store Channel
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        // Prevent duplicate channel
        if ($user->channel) {
            return redirect()
                ->route('channels.show', $user->channel->handle);
        }

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'handle' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                'unique:channels,handle',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Store Avatar
        |--------------------------------------------------------------------------
        */

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request
                ->file('avatar')
                ->store(
                    'channels/avatars',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Store Banner
        |--------------------------------------------------------------------------
        */

        $bannerPath = null;

        if ($request->hasFile('banner')) {
            $bannerPath = $request
                ->file('banner')
                ->store(
                    'channels/banners',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Channel
        |--------------------------------------------------------------------------
        */

        $channel = Channel::create([

            'user_id' => $user->id,

            'name' => $validated['name'],

            'handle' => ltrim(
                $validated['handle'],
                '@'
            ),

            'description' =>
                $validated['description'] ?? null,

            'avatar' => $avatarPath,

            'banner' => $bannerPath,

            'subscriber_count' => 0,

            'video_count' => 0,

            'total_views' => 0,

        ]);


        return redirect()
            ->route(
                'channels.show',
                $channel->handle
            )
            ->with(
                'success',
                'Your channel has been created successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Public Channel Page
    |--------------------------------------------------------------------------
    */

    public function show(string $handle)
    {
        $handle = ltrim($handle, '@');


        /*
        |--------------------------------------------------------------------------
        | Find Channel
        |--------------------------------------------------------------------------
        */

        $channel = Channel::with('user')
            ->where(
                'handle',
                $handle
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Channel Videos
        |--------------------------------------------------------------------------
        */

        $videos = $channel->videos()
            ->with([
                'channel',
                'category',
            ])
            ->where(
                'status',
                'published'
            )
            ->where(
                'visibility',
                'public'
            )
            ->latest('published_at')
            ->paginate(12);


        /*
        |--------------------------------------------------------------------------
        | Subscription Status
        |--------------------------------------------------------------------------
        */

        $isSubscribed = false;

        if (Auth::check()) {

            $isSubscribed = $channel
                ->subscriptions()
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::where(
            'is_active',
            true
        )
            ->orderBy('sort_order')
            ->get();


        return view(
            'channels.show',
            compact(
                'channel',
                'videos',
                'isSubscribed',
                'categories'
            )
        );
    }
}