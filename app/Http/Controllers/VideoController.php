<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Channel;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Video
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = Auth::user();
        $channel = $user->channel;

        if (!$channel) {
            return redirect()
                ->route('creator.channel.create')
                ->with('error', 'Create your channel first.');
        }

        $categories = Cache::remember(
            'active_video_categories',
            now()->addHours(6),
            function () {
                return Category::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        );

        return view(
            'videos.create',
            compact('channel', 'categories')
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
                ->with('error', 'Create your channel first.');
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
                'max:131072',
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

        $slug = Str::slug($validated['title']);

        $originalSlug = $slug;
        $counter = 1;

        while (Video::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | Store Video File
        |--------------------------------------------------------------------------
        */

        $videoPath = $request
            ->file('video')
            ->store('videos', 'public');


        /*
        |--------------------------------------------------------------------------
        | Store Thumbnail
        |--------------------------------------------------------------------------
        */

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request
                ->file('thumbnail')
                ->store('thumbnails', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Create Video
        |--------------------------------------------------------------------------
        */

        $video = DB::transaction(function () use ($user, $channel, $validated, $slug, $videoPath, $thumbnailPath) {

            $video = Video::create([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
                'category_id' => $validated['category_id'] ?? null,

                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,

                'video_path' => $videoPath,
                'thumbnail_path' => $thumbnailPath,

                'duration' => 0,

                'visibility' => $validated['visibility'],
                'status' => 'published',

                'views_count' => 0,
                'likes_count' => 0,
                'dislikes_count' => 0,
                'comments_count' => 0,

                'published_at' => now(),
            ]);


            $channel->increment('video_count');


            return $video;
        });


        /*
        |--------------------------------------------------------------------------
        | Notify Subscribers
        |--------------------------------------------------------------------------
        */

        $subscriberIds = Subscription::where(
            'channel_id',
            $channel->id
        )->pluck('user_id');


        foreach ($subscriberIds as $subscriberId) {

            Notification::create([
                'user_id' => $subscriberId,

                'type' => 'new_video',

                'title' => 'New video uploaded',

                'message' => $channel->name .
                    ' uploaded a new video: "' .
                    $video->title .
                    '"',

                'url' => route(
                    'videos.show',
                    $video->slug
                ),

                'actor_id' => $user->id,

                'is_read' => false,

                'read_at' => null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('creator.dashboard')
            ->with(
                'success',
                'Video uploaded successfully!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Video
    |--------------------------------------------------------------------------
    */

    public function show(string $slug)
    {
        $video = Video::with([
            'channel',
            'category',
        ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Private Video Access
        |--------------------------------------------------------------------------
        */

        if ($video->visibility === 'private') {

            if (
                !Auth::check()
                || Auth::id() !== $video->user_id
            ) {
                abort(404);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Count View Once Per Session
        |--------------------------------------------------------------------------
        */

        $sessionKey = 'video_viewed_' . $video->id;

        if (!session()->has($sessionKey)) {

            $video->increment('views_count');

            $video->channel->increment(
                'total_views'
            );

            session()->put(
                $sessionKey,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Related Videos
        |--------------------------------------------------------------------------
        */

        $relatedVideos = Video::with([
            'channel',
            'category',
        ])
            ->where('id', '!=', $video->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->when(
                $video->category_id,
                function ($query) use ($video) {
                    $query->where(
                        'category_id',
                        $video->category_id
                    );
                }
            )
            ->latest('published_at')
            ->take(12)
            ->get();


        return view(
            'videos.show',
            compact(
                'video',
                'relatedVideos'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Track Watch Time
    |--------------------------------------------------------------------------
    */

    public function trackWatch(
        Request $request,
        Video $video
    ): JsonResponse {

        $validated = $request->validate([
            'watched_seconds' => [
                'required',
                'integer',
                'min:0',
                'max:30',
            ],

            'last_position' => [
                'required',
                'integer',
                'min:0',
            ],

            'completed' => [
                'nullable',
                'boolean',
            ],
        ]);


        $userId = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Session
        |--------------------------------------------------------------------------
        */

        $sessionId = session()->getId();

        if (!$sessionId) {
            $sessionId = 'guest_' . Str::uuid();
        }


        /*
        |--------------------------------------------------------------------------
        | IP Hash
        |--------------------------------------------------------------------------
        */

        $ipHash = hash(
            'sha256',
            $request->ip() ?? '0.0.0.0'
        );


        /*
        |--------------------------------------------------------------------------
        | Find Existing View
        |--------------------------------------------------------------------------
        */

        $viewQuery = VideoView::where(
            'video_id',
            $video->id
        );


        if ($userId) {

            $viewQuery->where(
                'user_id',
                $userId
            );

        } else {

            $viewQuery
                ->whereNull('user_id')
                ->where(
                    'session_id',
                    $sessionId
                );
        }


        $view = $viewQuery->first();


        /*
        |--------------------------------------------------------------------------
        | Create / Update Watch Record
        |--------------------------------------------------------------------------
        */

        if (!$view) {

            $view = VideoView::create([
                'video_id' => $video->id,

                'user_id' => $userId,

                'session_id' => $sessionId,

                'ip_hash' => $ipHash,

                'last_position' => $validated['last_position'],

                'watched_seconds' =>
                    $validated['watched_seconds'],

                'completed' =>
                    (bool) (
                        $validated['completed'] ?? false
                    ),
            ]);

        } else {

            if ($validated['watched_seconds'] > 0) {

                $view->increment(
                    'watched_seconds',
                    $validated['watched_seconds']
                );
            }


            $view->update([
                'last_position' =>
                    $validated['last_position'],

                'completed' =>
                    $view->completed
                    || (bool) (
                        $validated['completed'] ?? false
                    ),
            ]);


            $view->refresh();
        }


        return response()->json([
            'success' => true,

            'watched_seconds' =>
                (int) $view->watched_seconds,

            'last_position' =>
                (int) $view->last_position,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Like / Dislike
    |--------------------------------------------------------------------------
    */

    public function like(
        Request $request,
        Video $video
    ) {

        $user = Auth::user();

        $type = $request->input('type');


        if (
            !in_array(
                $type,
                ['like', 'dislike'],
                true
            )
        ) {
            return back();
        }


        $existingLike = Like::where(
            'user_id',
            $user->id
        )
            ->where(
                'video_id',
                $video->id
            )
            ->first();


        $shouldNotify = false;


        /*
        |--------------------------------------------------------------------------
        | Remove Existing Reaction
        |--------------------------------------------------------------------------
        */

        if (
            $existingLike
            && $existingLike->type === $type
        ) {

            $existingLike->delete();

        }


        /*
        |--------------------------------------------------------------------------
        | Change Reaction
        |--------------------------------------------------------------------------
        */ elseif ($existingLike) {

            $oldType = $existingLike->type;

            $existingLike->update([
                'type' => $type,
            ]);


            if (
                $oldType !== 'like'
                && $type === 'like'
            ) {
                $shouldNotify = true;
            }

        }


        /*
        |--------------------------------------------------------------------------
        | New Reaction
        |--------------------------------------------------------------------------
        */ else {

            Like::create([
                'user_id' => $user->id,

                'video_id' => $video->id,

                'type' => $type,
            ]);


            if ($type === 'like') {
                $shouldNotify = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Like Counts
        |--------------------------------------------------------------------------
        */

        $video->update([
            'likes_count' =>
                $video->likes()
                    ->where('type', 'like')
                    ->count(),

            'dislikes_count' =>
                $video->likes()
                    ->where('type', 'dislike')
                    ->count(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Like Notification
        |--------------------------------------------------------------------------
        */

        if ($shouldNotify) {

            $channelOwnerId =
                $video->channel?->user_id;


            if (
                $channelOwnerId
                && $channelOwnerId !== $user->id
            ) {

                Notification::create([
                    'user_id' =>
                        $channelOwnerId,

                    'type' =>
                        'video_like',

                    'title' =>
                        'New like',

                    'message' =>
                        $user->name .
                        ' liked your video "' .
                        $video->title .
                        '"',

                    'url' =>
                        route(
                            'videos.show',
                            $video->slug
                        ),

                    'actor_id' =>
                        $user->id,

                    'is_read' =>
                        false,

                    'read_at' =>
                        null,
                ]);
            }
        }


        return back();
    }


    /*
    |--------------------------------------------------------------------------
    | Subscribe / Unsubscribe
    |--------------------------------------------------------------------------
    */

    public function subscribe(
        Request $request,
        Channel $channel
    ) {

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Prevent Self Subscription
        |--------------------------------------------------------------------------
        */

        if (
            $channel->user_id === $user->id
        ) {

            return back()->with(
                'error',
                'You cannot subscribe to your own channel.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Subscription
        |--------------------------------------------------------------------------
        */

        $subscription = Subscription::where(
            'user_id',
            $user->id
        )
            ->where(
                'channel_id',
                $channel->id
            )
            ->first();


        if ($subscription) {

            $subscription->delete();


            if ($channel->subscriber_count > 0) {

                $channel->decrement(
                    'subscriber_count'
                );
            }


            return back()->with(
                'success',
                'Unsubscribed successfully.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Subscription
        |--------------------------------------------------------------------------
        */

        Subscription::create([
            'user_id' =>
                $user->id,

            'channel_id' =>
                $channel->id,
        ]);


        $channel->increment(
            'subscriber_count'
        );


        /*
        |--------------------------------------------------------------------------
        | Subscriber Notification
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' =>
                $channel->user_id,

            'type' =>
                'new_subscriber',

            'title' =>
                'New subscriber',

            'message' =>
                $user->name .
                ' subscribed to your channel.',

            'url' =>
                route(
                    'channels.show',
                    $channel->handle
                ),

            'actor_id' =>
                $user->id,

            'is_read' =>
                false,

            'read_at' =>
                null,
        ]);


        return back()->with(
            'success',
            'Subscribed successfully.'
        );
    }
}