@extends('layouts.app')

@section('title', $video->title . ' - Tradim')

@section('content')

    @php
        $userLike = null;
        $isSubscribed = false;

        if (Auth::check()) {
            $userLike = $video->likes()
                ->where('user_id', Auth::id())
                ->first();

            $isSubscribed = $video->channel
                ->subscriptions()
                ->where('user_id', Auth::id())
                ->exists();
        }
    @endphp

    <div class="tradim-watch">
        <div class="row g-4">

            {{-- MAIN VIDEO --}}
            <div class="col-xl-8 col-lg-8">
                <div class="player-wrapper">
                    <video class="tradim-player" controls playsinline preload="metadata" id="tradimVideoPlayer"
                        poster="{{ $video->thumbnail_path ? asset('storage/' . $video->thumbnail_path) : '' }}">
                        <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                </div>

                {{-- VIDEO TITLE --}}
                <div class="watch-info">
                    <h1>{{ $video->title }}</h1>
                    <div class="watch-meta">
                        <span>{{ number_format($video->views_count) }} views</span>
                        <span>•</span>
                        <span>{{ $video->published_at ? $video->published_at->diffForHumans() : 'Recently' }}</span>
                    </div>
                </div>

                {{-- CHANNEL BAR --}}
                <div class="channel-bar">
                    <div class="watch-channel-avatar">
                        @if($video->channel->avatar)
                            <img src="{{ asset('storage/' . $video->channel->avatar) }}" alt="{{ $video->channel->name }}">
                        @else
                            {{ strtoupper(substr($video->channel->name, 0, 1)) }}
                        @endif
                    </div>

                    <div class="channel-details">
                        <h3>
                            {{ $video->channel->name }}
                            @if($video->channel->is_verified)
                                <i class="bi bi-patch-check-fill verified"></i>
                            @endif
                        </h3>
                        <p>
                            {{ '@' . $video->channel->handle }} • {{ number_format($video->channel->subscriber_count) }}
                            subscribers
                        </p>
                    </div>

                    @auth
                        @if(Auth::id() !== $video->user_id)
                            <form method="POST" action="{{ route('channels.subscribe', $video->channel->id) }}">
                                @csrf
                                <button type="submit" class="subscribe-btn {{ $isSubscribed ? 'subscribed' : '' }}">
                                    @if($isSubscribed)
                                        <i class="bi bi-check-lg"></i> Subscribed
                                    @else
                                        <i class="bi bi-bell"></i> Subscribe
                                    @endif
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="subscribe-btn">Subscribe</a>
                    @endauth
                </div>

                {{-- DESCRIPTION --}}
                <div class="description-box">
                    @if($video->description)
                        <div class="description-text">
                            {!! nl2br(e($video->description)) !!}
                        </div>
                    @else
                        <p class="no-description">No description provided.</p>
                    @endif
                </div>

                {{-- ACTIONS --}}
                <div class="watch-actions">
                    @auth
                        <form method="POST" action="{{ route('videos.like', $video->id) }}">
                            @csrf
                            <input type="hidden" name="type" value="like">
                            <button type="submit" class="watch-action {{ $userLike?->type === 'like' ? 'active' : '' }}">
                                <i class="bi bi-hand-thumbs-up"></i> {{ number_format($video->likes_count) }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('videos.like', $video->id) }}">
                            @csrf
                            <input type="hidden" name="type" value="dislike">
                            <button type="submit" class="watch-action {{ $userLike?->type === 'dislike' ? 'active' : '' }}">
                                <i class="bi bi-hand-thumbs-down"></i> {{ number_format($video->dislikes_count) }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="watch-action">
                            <i class="bi bi-hand-thumbs-up"></i> {{ number_format($video->likes_count) }}
                        </a>
                        <a href="{{ route('login') }}" class="watch-action">
                            <i class="bi bi-hand-thumbs-down"></i>
                        </a>
                    @endauth

                    <button type="button" class="watch-action"
                        onclick="navigator.clipboard.writeText(window.location.href); this.innerHTML = '<i class=\'bi bi-check-lg\'></i> Copied';">
                        <i class="bi bi-share"></i> Share
                    </button>
                </div>

                {{-- COMMENTS --}}
                <div class="comments-box">
                    <h2>Comments <span>{{ number_format($video->comments_count) }}</span></h2>

                    @auth
                        <form method="POST" action="{{ route('comments.store', $video->id) }}" class="comment-form">
                            @csrf
                            <div class="comment-input">
                                <div class="comment-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <textarea name="comment" rows="2" placeholder="Add a comment..." required></textarea>
                            </div>
                            <div class="comment-submit">
                                <button type="submit" class="btn-tradim">
                                    <i class="bi bi-send"></i> Comment
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="login-comment">
                            <p>Sign in to join the conversation.</p>
                            <a href="{{ route('login') }}" class="btn-tradim">Sign In</a>
                        </div>
                    @endauth

                    {{-- COMMENTS LIST --}}
                    <div class="comments-list">
                        @forelse($video->comments()->with('user')->whereNull('parent_id')->latest()->get() as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="comment-content">
                                    <div class="comment-user">
                                        {{ $comment->user->name }}
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p>{{ $comment->body }}</p>
                                    @auth
                                        @if(Auth::id() === $comment->user_id)
                                            <form method="POST" action="{{ route('comments.destroy', $comment->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-comment">Delete</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="no-comments">
                                <i class="bi bi-chat-left-text"></i>
                                <p>No comments yet.</p>
                                <span>Be the first to start the conversation.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RELATED VIDEOS --}}
            <div class="col-xl-4 col-lg-4">
                <div class="related-header">
                    <h2>Up Next</h2>
                    <p>More videos you may like</p>
                </div>

                <div class="related-list">
                    @forelse($relatedVideos as $related)
                        <a href="{{ route('videos.show', $related->slug) }}" class="related-card">
                            <div class="related-thumbnail">
                                @if($related->thumbnail_path)
                                    <img src="{{ asset('storage/' . $related->thumbnail_path) }}" alt="{{ $related->title }}">
                                @else
                                    <div class="related-default">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="related-info">
                                <h3>{{ $related->title }}</h3>
                                <p>{{ $related->channel->name }}</p>
                                <span>{{ number_format($related->views_count) }} views •
                                    {{ $related->published_at ? $related->published_at->diffForHumans() : 'Recently' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="no-related">No related videos yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <style>
        .tradim-watch {
            color: #f8fafc;
        }

        .player-wrapper {
            width: 100%;
            background: #050811;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #202a40;
        }

        .tradim-player {
            display: block;
            width: 100%;
            max-height: 620px;
            aspect-ratio: 16 / 9;
            background: #000;
        }

        .watch-info {
            padding: 20px 0 10px;
        }

        .watch-info h1 {
            color: #ffffff;
            font-size: 24px;
            line-height: 1.35;
            font-weight: 800;
            margin: 0 0 10px;
        }

        .watch-meta {
            display: flex;
            gap: 8px;
            color: #8491a7;
            font-size: 13px;
        }

        .channel-bar {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 18px 0;
            border-top: 1px solid #222c40;
            border-bottom: 1px solid #222c40;
        }

        .watch-channel-avatar {
            flex: 0 0 48px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            color: #ffffff;
            font-weight: 800;
        }

        .watch-channel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .channel-details {
            flex: 1;
        }

        .channel-details h3 {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .channel-details p {
            color: #8491a7;
            font-size: 12px;
            margin: 0;
        }

        .verified {
            color: #60a5fa;
        }

        .subscribe-btn {
            border: 0;
            border-radius: 9px;
            padding: 10px 18px;
            background: #ffffff;
            color: #111827 !important;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .subscribe-btn:hover {
            background: #e5e7eb;
        }

        .subscribe-btn.subscribed {
            background: #272f42;
            color: #ffffff !important;
            border: 1px solid #3b465e;
        }

        .description-box {
            margin-top: 18px;
            padding: 17px;
            border-radius: 12px;
            background: #121a2b;
            border: 1px solid #273149;
        }

        .description-text {
            color: #d1d9e8;
            font-size: 14px;
            line-height: 1.7;
            white-space: normal;
        }

        .no-description {
            color: #64748b;
            margin: 0;
        }

        .watch-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .watch-actions form {
            margin: 0;
        }

        .watch-action {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 15px;
            border-radius: 20px;
            border: 1px solid #2b354c;
            background: #151c2d;
            color: #cbd5e1;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .watch-action:hover {
            color: #ffffff;
            border-color: #7c3aed;
        }

        .watch-action.active {
            background: #31205f;
            border-color: #7c3aed;
            color: #c4b5fd;
        }

        .comments-box {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #222c40;
        }

        .comments-box h2 {
            color: #ffffff;
            font-size: 20px;
            margin-bottom: 22px;
        }

        .comments-box h2 span {
            color: #64748b;
            font-size: 14px;
            margin-left: 5px;
        }

        .comment-form {
            margin-bottom: 30px;
        }

        .comment-input {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #7c3aed;
            color: #ffffff;
            font-weight: 700;
        }

        .comment-input textarea {
            width: 100%;
            resize: vertical;
            min-height: 70px;
            padding: 12px 15px;
            background: #121a2b;
            border: 1px solid #2a354c;
            border-radius: 10px;
            color: #ffffff;
            outline: none;
        }

        .comment-input textarea:focus {
            border-color: #7c3aed;
        }

        .comment-input textarea::placeholder {
            color: #64748b;
        }

        .comment-submit {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .login-comment {
            padding: 25px;
            text-align: center;
            border-radius: 12px;
            background: #121a2b;
            border: 1px solid #273149;
        }

        .login-comment p {
            color: #94a3b8;
        }

        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .comment-item {
            display: flex;
            gap: 12px;
        }

        .comment-content {
            flex: 1;
            min-width: 0;
        }

        .comment-user {
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .comment-user span {
            color: #64748b;
            font-size: 11px;
            font-weight: 400;
            margin-left: 7px;
        }

        .comment-content p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .delete-comment {
            margin-top: 8px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #ef4444;
            font-size: 12px;
            cursor: pointer;
        }

        .delete-comment:hover {
            color: #f87171;
        }

        .no-comments {
            padding: 35px 20px;
            text-align: center;
            background: #121a2b;
            border: 1px solid #273149;
            border-radius: 12px;
        }

        .no-comments i {
            color: #8b5cf6;
            font-size: 30px;
        }

        .no-comments p {
            color: #ffffff;
            font-weight: 700;
            margin: 10px 0 3px;
        }

        .no-comments span {
            color: #64748b;
            font-size: 12px;
        }

        .related-header {
            margin-bottom: 18px;
        }

        .related-header h2 {
            color: #ffffff;
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .related-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        .related-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .related-card {
            display: flex;
            gap: 12px;
            color: inherit;
            text-decoration: none;
        }

        .related-thumbnail {
            flex: 0 0 155px;
            width: 155px;
            height: 88px;
            border-radius: 9px;
            overflow: hidden;
            background: #151c2d;
        }

        .related-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .2s;
        }

        .related-card:hover .related-thumbnail img {
            transform: scale(1.04);
        }

        .related-default {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1b2237;
            color: #8b5cf6;
            font-size: 30px;
        }

        .related-info {
            min-width: 0;
        }

        .related-info h3 {
            color: #f1f5f9;
            font-size: 14px;
            line-height: 1.35;
            font-weight: 700;
            margin: 0 0 7px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-info p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0 0 4px;
        }

        .related-info span {
            color: #64748b;
            font-size: 11px;
        }

        .no-related {
            padding: 30px;
            text-align: center;
            color: #64748b;
            border: 1px solid #273149;
            border-radius: 12px;
        }

        @media (max-width: 991px) {
            .related-thumbnail {
                flex-basis: 180px;
                width: 180px;
                height: 100px;
            }
        }

        @media (max-width: 600px) {
            .watch-info h1 {
                font-size: 19px;
            }

            .channel-bar {
                flex-wrap: wrap;
            }

            .subscribe-btn {
                width: 100%;
            }

            .watch-actions {
                flex-wrap: wrap;
            }

            .related-thumbnail {
                flex-basis: 140px;
                width: 140px;
                height: 80px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const player = document.getElementById('tradimVideoPlayer');

            if (!player) {
                return;
            }

            const trackingUrl = @json(route('videos.watch-time', $video->id));
            const csrfToken = @json(csrf_token());

            let lastTime = 0;
            let pendingSeconds = 0;
            let sending = false;

            /*
            |--------------------------------------------------------------------------
            | Calculate actual watched time
            |--------------------------------------------------------------------------
            */

            player.addEventListener('play', function () {
                lastTime = player.currentTime;
            });

            player.addEventListener('timeupdate', function () {

                if (player.paused || player.ended) {
                    lastTime = player.currentTime;
                    return;
                }

                const currentTime = player.currentTime;

                const difference = currentTime - lastTime;

                /*
                | Only count realistic playback movement.
                | If user seeks from 10 sec to 500 sec,
                | don't count 490 seconds as watched.
                */

                if (difference > 0 && difference <= 2) {
                    pendingSeconds += difference;
                }

                lastTime = currentTime;
            });

            /*
            |--------------------------------------------------------------------------
            | Send watch time to server
            |--------------------------------------------------------------------------
            */

            async function sendWatchTime(useBeacon = false) {

                const seconds = Math.floor(pendingSeconds);

                if (seconds <= 0 && !player.ended) {
                    return;
                }

                /*
                | Don't send more than 60 seconds in one request.
                */

                const secondsToSend = Math.min(seconds, 60);

                if (secondsToSend <= 0 && !player.ended) {
                    return;
                }

                const currentPosition = Math.floor(
                    player.currentTime || 0
                );

                const completed = player.ended ? 1 : 0;

                const formData = new FormData();

                formData.append('_token', csrfToken);
                formData.append(
                    'watched_seconds',
                    secondsToSend
                );
                formData.append(
                    'last_position',
                    currentPosition
                );
                formData.append(
                    'completed',
                    completed
                );

                /*
                |--------------------------------------------------------------------------
                | Beacon
                |--------------------------------------------------------------------------
                */

                if (useBeacon && navigator.sendBeacon) {

                    const sent = navigator.sendBeacon(
                        trackingUrl,
                        formData
                    );

                    if (sent) {
                        pendingSeconds -= secondsToSend;
                    }

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Prevent duplicate requests
                |--------------------------------------------------------------------------
                */

                if (sending) {
                    return;
                }

                sending = true;

                try {

                    const response = await fetch(
                        trackingUrl,
                        {
                            method: 'POST',

                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },

                            body: formData,

                            credentials: 'same-origin'
                        }
                    );

                    if (response.ok) {

                        pendingSeconds -= secondsToSend;

                        if (pendingSeconds < 0) {
                            pendingSeconds = 0;
                        }

                    } else {

                        console.error(
                            'Watch time request failed:',
                            response.status
                        );
                    }

                } catch (error) {

                    console.error(
                        'Watch time error:',
                        error
                    );

                } finally {

                    sending = false;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Save every 5 seconds
            |--------------------------------------------------------------------------
            */

            setInterval(function () {

                if (!player.paused && !player.ended) {

                    if (pendingSeconds >= 5) {
                        sendWatchTime(false);
                    }

                }

            }, 1000);

            /*
            |--------------------------------------------------------------------------
            | Pause
            |--------------------------------------------------------------------------
            */

            player.addEventListener('pause', function () {
                sendWatchTime(false);
            });

            /*
            |--------------------------------------------------------------------------
            | Video ended
            |--------------------------------------------------------------------------
            */

            player.addEventListener('ended', function () {
                sendWatchTime(false);
            });

            /*
            |--------------------------------------------------------------------------
            | Seeking
            |--------------------------------------------------------------------------
            */

            player.addEventListener('seeking', function () {
                lastTime = player.currentTime;
            });

            player.addEventListener('seeked', function () {
                lastTime = player.currentTime;
            });

            /*
            |--------------------------------------------------------------------------
            | Tab hidden
            |--------------------------------------------------------------------------
            */

            document.addEventListener('visibilitychange', function () {

                if (document.visibilityState === 'hidden') {

                    sendWatchTime(true);

                } else {

                    lastTime = player.currentTime;
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Page leave
            |--------------------------------------------------------------------------
            */

            window.addEventListener('pagehide', function () {

                sendWatchTime(true);

            });

        });
    </script>
@endsection