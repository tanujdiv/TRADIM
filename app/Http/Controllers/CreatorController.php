<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreatorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Creator Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create');
        }

        $videos = $channel->videos()
            ->latest()
            ->paginate(10);

        return view(
            'creator.dashboard',
            compact('channel', 'videos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Channel Page
    |--------------------------------------------------------------------------
    */

    public function createChannel()
    {
        $user = Auth::user();

        if ($user->channel) {
            return redirect()
                ->route('creator.dashboard');
        }

        return view('creator.channel.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Store Channel
    |--------------------------------------------------------------------------
    */

    public function storeChannel(Request $request)
    {
        $user = Auth::user();

        if ($user->channel) {
            return redirect()
                ->route('creator.dashboard');
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
                'alpha_dash',
                'unique:channels,handle',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        $slug = Str::slug(
            $validated['name']
        );


        $originalSlug = $slug;

        $counter = 1;


        while (
            Channel::where(
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


        $channel = Channel::create([

            'user_id' => $user->id,

            'name' => $validated['name'],

            'slug' => $slug,

            'handle' =>
                strtolower(
                    $validated['handle']
                ),

            'description' =>
                $validated['description'] ?? null,

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