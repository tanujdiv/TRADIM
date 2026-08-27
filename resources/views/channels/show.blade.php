@extends('layouts.app')

@section('title', $channel->name . ' - Tradim')

@section('content')

    <div class="tradim-channel-page">

        {{-- =========================================================
        CHANNEL BANNER
        ========================================================== --}}

        <div class="channel-banner">

            @if($channel->banner)

                <img src="{{ asset('storage/' . $channel->banner) }}" alt="{{ $channel->name }} banner">

            @else

                <div class="default-banner">
                    <div class="banner-glow"></div>
                </div>

            @endif

        </div>


        {{-- =========================================================
        CHANNEL HEADER
        ========================================================== --}}

        <div class="channel-header">

            <div class="channel-profile">

                <div class="channel-avatar">

                    @if($channel->avatar)

                        <img src="{{ asset('storage/' . $channel->avatar) }}" alt="{{ $channel->name }}">

                    @else

                                    {{ strtoupper(
                            substr($channel->name, 0, 1)
                        ) }}

                    @endif

                </div>


                <div class="channel-main-info">

                    <h1>

                        {{ $channel->name }}

                        @if($channel->is_verified)

                            <i class="bi bi-patch-check-fill verified-icon"></i>

                        @endif

                    </h1>


                    <div class="channel-handle">

                        @{{ $channel->handle }}

                    </div>


                    <div class="channel-stats">

                        <span>
                            {{ number_format($channel->subscriber_count) }}
                            subscribers
                        </span>

                        <span>•</span>

                        <span>
                            {{ number_format($channel->video_count) }}
                            videos
                        </span>

                        <span>•</span>

                        <span>
                            {{ number_format($channel->total_views) }}
                            views
                        </span>

                    </div>

                </div>

            </div>


            {{-- SUBSCRIBE BUTTON --}}

            <div class="channel-actions">

                @auth

                    @if(Auth::id() !== $channel->user_id)

                            <form method="POST" action="{{ route(
                            'channels.subscribe',
                            $channel->id
                        ) }}">

                                @csrf

                                <button type="submit" class="channel-subscribe-btn
                                            {{ $isSubscribed ? 'subscribed' : '' }}">

                                    @if($isSubscribed)

                                        <i class="bi bi-check-lg"></i>

                                        Subscribed

                                    @else

                                        <i class="bi bi-bell"></i>

                                        Subscribe

                                    @endif

                                </button>

                            </form>

                    @else

                        <a href="{{ route('creator.dashboard') }}" class="manage-channel-btn">

                            <i class="bi bi-gear"></i>

                            Manage Channel

                        </a>

                    @endif

                @else

                    <a href="{{ route('login') }}" class="channel-subscribe-btn">

                        <i class="bi bi-bell"></i>

                        Subscribe

                    </a>

                @endauth

            </div>

        </div>


        {{-- =========================================================
        CHANNEL DESCRIPTION
        ========================================================== --}}

        @if($channel->description)

            <div class="channel-description">

                {{ $channel->description }}

            </div>

        @endif


        {{-- =========================================================
        CHANNEL NAVIGATION
        ========================================================== --}}

        <div class="channel-tabs">

            <a href="#videos" class="channel-tab active">

                Videos

            </a>

            <a href="#about" class="channel-tab">

                About

            </a>

        </div>


        {{-- =========================================================
        VIDEOS
        ========================================================== --}}

        <section id="videos" class="channel-video-section">

            <div class="section-heading">

                <div>

                    <h2>
                        Videos
                    </h2>

                    <p>
                        Latest videos from {{ $channel->name }}
                    </p>

                </div>

            </div>


            @if($videos->count())

                <div class="channel-video-grid">

                    @foreach($videos as $video)

                            <a href="{{ route(
                            'videos.show',
                            $video->slug
                        ) }}" class="channel-video-card">

                                {{-- Thumbnail --}}

                                <div class="channel-video-thumbnail">

                                    @if($video->thumbnail_path)

                                                <img src="{{ asset(
                                            'storage/' .
                                            $video->thumbnail_path
                                        ) }}" alt="{{ $video->title }}">

                                    @else

                                        <div class="video-placeholder">

                                            <i class="bi bi-play-fill"></i>

                                        </div>

                                    @endif

                                </div>


                                {{-- Info --}}

                                <div class="channel-video-info">

                                    <h3>

                                        {{ $video->title }}

                                    </h3>


                                    <div class="video-meta">

                                        <span>

                                            {{ number_format(
                            $video->views_count
                        ) }}

                                            views

                                        </span>

                                        <span>•</span>

                                        <span>

                                            {{ $video->published_at
                            ? $video->published_at->diffForHumans()
                            : 'Recently'
                                                    }}

                                        </span>

                                    </div>

                                </div>

                            </a>

                    @endforeach

                </div>


                {{-- Pagination --}}

                <div class="channel-pagination">

                    {{ $videos->links() }}

                </div>

            @else

                <div class="empty-channel">

                    <div class="empty-icon">

                        <i class="bi bi-camera-video"></i>

                    </div>

                    <h3>
                        No videos yet
                    </h3>

                    <p>
                        This channel hasn't uploaded any public videos yet.
                    </p>

                </div>

            @endif

        </section>


        {{-- =========================================================
        ABOUT
        ========================================================== --}}

        <section id="about" class="channel-about">

            <h2>
                About {{ $channel->name }}
            </h2>


            <div class="about-card">

                <div class="about-row">

                    <i class="bi bi-person"></i>

                    <div>

                        <span>Channel</span>

                        <strong>
                            {{ $channel->name }}
                        </strong>

                    </div>

                </div>


                <div class="about-row">

                    <i class="bi bi-people"></i>

                    <div>

                        <span>Subscribers</span>

                        <strong>
                            {{ number_format(
        $channel->subscriber_count
    ) }}
                        </strong>

                    </div>

                </div>


                <div class="about-row">

                    <i class="bi bi-camera-video"></i>

                    <div>

                        <span>Videos</span>

                        <strong>
                            {{ number_format(
        $channel->video_count
    ) }}
                        </strong>

                    </div>

                </div>


                <div class="about-row">

                    <i class="bi bi-eye"></i>

                    <div>

                        <span>Total Views</span>

                        <strong>
                            {{ number_format(
        $channel->total_views
    ) }}
                        </strong>

                    </div>

                </div>

            </div>

        </section>

    </div>


    <style>
        /* =========================================================
    CHANNEL PAGE
    ========================================================= */

        .tradim-channel-page {
            color: #f8fafc;
        }


        /* =========================================================
    BANNER
    ========================================================= */

        .channel-banner {
            width: 100%;
            height: 250px;
            overflow: hidden;
            border-radius: 16px;
            border: 1px solid #202a40;
            background: #0b1120;
        }

        .channel-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .default-banner {
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 20%,
                    rgba(124, 58, 237, .45),
                    transparent 35%),
                radial-gradient(circle at 80% 80%,
                    rgba(236, 72, 153, .35),
                    transparent 35%),
                #0b1120;
        }


        /* =========================================================
    HEADER
    ========================================================= */

        .channel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            padding: 28px 5px;
        }

        .channel-profile {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .channel-avatar {
            width: 100px;
            height: 100px;
            flex: 0 0 100px;
            border-radius: 50%;
            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: center;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: white;
            font-size: 35px;
            font-weight: 800;

            border: 4px solid #111827;
        }

        .channel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .channel-main-info h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .channel-handle {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .channel-stats {
            display: flex;
            gap: 8px;
            color: #64748b;
            font-size: 13px;
        }

        .verified-icon {
            color: #60a5fa;
            font-size: 17px;
        }


        /* =========================================================
    BUTTONS
    ========================================================= */

        .channel-subscribe-btn,
        .manage-channel-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            border: 0;
            border-radius: 10px;

            padding: 11px 20px;

            background: #ffffff;
            color: #111827 !important;

            font-weight: 700;
            text-decoration: none;
        }

        .channel-subscribe-btn:hover,
        .manage-channel-btn:hover {
            background: #e5e7eb;
        }

        .channel-subscribe-btn.subscribed {
            background: #252e42;
            color: #ffffff !important;
            border: 1px solid #3b465e;
        }


        /* =========================================================
    DESCRIPTION
    ========================================================= */

        .channel-description {
            max-width: 850px;

            padding: 16px 18px;

            border-radius: 12px;

            background: #121a2b;
            border: 1px solid #273149;

            color: #cbd5e1;

            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
    TABS
    ========================================================= */

        .channel-tabs {
            display: flex;
            gap: 30px;

            margin-top: 30px;

            border-bottom: 1px solid #273149;
        }

        .channel-tab {
            padding: 14px 4px;

            color: #64748b;

            text-decoration: none;

            font-weight: 700;

            border-bottom: 2px solid transparent;
        }

        .channel-tab:hover,
        .channel-tab.active {
            color: #ffffff;
            border-color: #7c3aed;
        }


        /* =========================================================
    SECTION
    ========================================================= */

        .channel-video-section {
            padding-top: 28px;
        }

        .section-heading {
            margin-bottom: 20px;
        }

        .section-heading h2 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 5px;
        }

        .section-heading p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }


        /* =========================================================
    VIDEO GRID
    ========================================================= */

        .channel-video-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 22px;
        }

        .channel-video-card {
            text-decoration: none;
            color: inherit;
        }

        .channel-video-thumbnail {
            width: 100%;
            aspect-ratio: 16 / 9;

            overflow: hidden;

            border-radius: 11px;

            background: #151c2d;

            border: 1px solid #273149;
        }

        .channel-video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;

            transition: .25s;
        }

        .channel-video-card:hover .channel-video-thumbnail img {
            transform: scale(1.04);
        }

        .video-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #1b2237;

            color: #8b5cf6;

            font-size: 40px;
        }

        .channel-video-info {
            padding-top: 10px;
        }

        .channel-video-info h3 {
            color: #f1f5f9;

            font-size: 14px;
            line-height: 1.45;

            font-weight: 700;

            margin: 0 0 7px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
        }

        .video-meta {
            display: flex;
            gap: 6px;

            color: #64748b;

            font-size: 11px;
        }


        /* =========================================================
    EMPTY
    ========================================================= */

        .empty-channel {
            padding: 70px 20px;

            text-align: center;

            border: 1px solid #273149;
            border-radius: 14px;

            background: #121a2b;
        }

        .empty-icon {
            color: #8b5cf6;
            font-size: 40px;
        }

        .empty-channel h3 {
            color: #ffffff;
            margin: 12px 0 5px;
        }

        .empty-channel p {
            color: #64748b;
            margin: 0;
        }


        /* =========================================================
    ABOUT
    ========================================================= */

        .channel-about {
            margin-top: 50px;
            padding-bottom: 50px;
        }

        .channel-about h2 {
            color: #ffffff;
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .about-card {
            max-width: 650px;

            padding: 20px;

            border-radius: 14px;

            background: #121a2b;
            border: 1px solid #273149;
        }

        .about-row {
            display: flex;
            align-items: center;

            gap: 15px;

            padding: 15px 0;

            border-bottom: 1px solid #273149;
        }

        .about-row:last-child {
            border-bottom: 0;
        }

        .about-row>i {
            width: 25px;

            color: #8b5cf6;

            font-size: 20px;
        }

        .about-row span {
            display: block;

            color: #64748b;

            font-size: 12px;

            margin-bottom: 3px;
        }

        .about-row strong {
            color: #ffffff;

            font-size: 14px;
        }


        /* =========================================================
    PAGINATION
    ========================================================= */

        .channel-pagination {
            margin-top: 30px;
        }

        .channel-pagination nav {
            display: flex;
            justify-content: center;
        }

        .channel-pagination .pagination {
            gap: 5px;
        }

        .channel-pagination .page-link {
            background: #121a2b;
            border-color: #273149;
            color: #cbd5e1;
        }

        .channel-pagination .page-link:hover {
            background: #7c3aed;
            color: #ffffff;
        }

        .channel-pagination .active .page-link {
            background: #7c3aed;
            border-color: #7c3aed;
            color: #ffffff;
        }


        /* =========================================================
    RESPONSIVE
    ========================================================= */

        @media (max-width: 1100px) {

            .channel-video-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

        }


        @media (max-width: 800px) {

            .channel-banner {
                height: 180px;
            }

            .channel-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .channel-profile {
                align-items: flex-start;
            }

            .channel-avatar {
                width: 80px;
                height: 80px;
                flex-basis: 80px;
            }

            .channel-main-info h1 {
                font-size: 22px;
            }

            .channel-video-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 550px) {

            .channel-banner {
                height: 130px;
                border-radius: 10px;
            }

            .channel-profile {
                gap: 12px;
            }

            .channel-avatar {
                width: 65px;
                height: 65px;
                flex-basis: 65px;
                font-size: 24px;
            }

            .channel-main-info h1 {
                font-size: 18px;
            }

            .channel-stats {
                flex-wrap: wrap;
            }

            .channel-video-grid {
                grid-template-columns: 1fr;
            }

            .channel-actions {
                width: 100%;
            }

            .channel-subscribe-btn,
            .manage-channel-btn {
                width: 100%;
                justify-content: center;
            }

        }
    </style>

@endsection