<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CreatorChannelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Channel Settings Page
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Please create your channel first.');
        }

        Gate::authorize('update', $channel);

        return view(
            'creator.channel.edit',
            compact('channel')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Channel
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $user = Auth::user();

        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Please create your channel first.');
        }

        Gate::authorize('update', $channel);

        /*
        |--------------------------------------------------------------------------
        | Normalize Handle
        |--------------------------------------------------------------------------
        */

        $handle = ltrim(
            trim((string) $request->input('handle')),
            '@'
        );

        $request->merge([
            'handle' => $handle,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

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
                Rule::unique('channels', 'handle')
                    ->ignore($channel->id),
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

            'remove_avatar' => [
                'nullable',
                'boolean',
            ],

            'remove_banner' => [
                'nullable',
                'boolean',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Avatar
        |--------------------------------------------------------------------------
        */

        $avatarPath = $channel->avatar;

        if ($request->hasFile('avatar')) {

            $newAvatarPath = $request
                ->file('avatar')
                ->store('channels/avatars', 'public');

            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = $newAvatarPath;

        } elseif ($request->boolean('remove_avatar')) {

            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Banner
        |--------------------------------------------------------------------------
        */

        $bannerPath = $channel->banner;

        if ($request->hasFile('banner')) {

            $newBannerPath = $request
                ->file('banner')
                ->store('channels/banners', 'public');

            if ($bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }

            $bannerPath = $newBannerPath;

        } elseif ($request->boolean('remove_banner')) {

            if ($bannerPath) {
                Storage::disk('public')->delete($bannerPath);
            }

            $bannerPath = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Channel
        |--------------------------------------------------------------------------
        */

        $channel->update([

            'name' =>
                $validated['name'],

            'handle' =>
                $validated['handle'],

            'description' =>
                $validated['description'] ?? null,

            'avatar' =>
                $avatarPath,

            'banner' =>
                $bannerPath,

        ]);

        return redirect()
            ->route('creator.channel.edit')
            ->with(
                'success',
                'Channel settings updated successfully.'
            );
    }
}