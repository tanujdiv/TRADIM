@extends('layouts.app')

@section('title', 'Tradim - Watch, Create & Share')

@section('content')

    <div class="tradim-home">


        {{-- =========================================================
        CATEGORY BAR
        ========================================================== --}}

        <div class="category-scroll">

            <a href="{{ route('home') }}" class="category-pill active">
                <i class="bi bi-house-fill"></i>
                All
            </a>


            @foreach($categories as $category)

                <button type="button" class="category-pill">

                    @if($category->icon)

                        <i class="bi {{ $category->icon }}"></i>

                    @endif

                    {{ $category->name }}

                </button>

            @endforeach

        </div>


        {{-- =========================================================
        HERO
        ========================================================== --}}

        <section class="tradim-hero">

            <div class="hero-content">

                <span class="hero-badge">

                    <i class="bi bi-stars"></i>

                    WELCOME TO TRADIM

                </span>


                <h1>

                    Watch.
                    <span>Create.</span>
                    Connect.

                </h1>


                <p>

                    Discover amazing videos, follow your favourite
                    creators and build your own audience on Tradim.

                </p>


                @guest

                    <div class="hero-buttons">

                        <a href="{{ route('register') }}" class="btn-tradim">

                            <i class="bi bi-person-plus"></i>

                            Start Creating

                        </a>


                        <a href="{{ route('login') }}" class="btn-tradim-outline">

                            Sign In

                        </a>

                    </div>

                @else

                    <div class="hero-buttons">

                        <a href="{{ route('videos.create') }}" class="btn-tradim">

                            <i class="bi bi-cloud-arrow-up"></i>

                            Upload Video

                        </a>


                        <a href="{{ route('creator.dashboard') }}" class="btn-tradim-outline">

                            Creator Studio

                        </a>

                    </div>

                @endguest

            </div>


            <div class="hero-decoration">

                <div class="hero-play">

                    <i class="bi bi-play-fill"></i>

                </div>

            </div>

        </section>


        {{-- =========================================================
        VIDEO SECTION
        ========================================================== --}}

        <section class="video-section">

            <div class="section-header">

                <div>

                    <h2>
                        Latest Videos
                    </h2>

                    <p>
                        Fresh content from Tradim creators
                    </p>

                </div>

            </div>


            @if($videos->count())

                <div class="video-grid">

                    @foreach($videos as $video)

                            <a href="{{ route(
                            'videos.show',
                            $video->slug
                        ) }}" class="video-card">

                                {{-- Thumbnail --}}

                                <div class="video-thumbnail">

                                    @if($video->thumbnail_path)

                                                <img src="{{ asset(
                                            'storage/' .
                                            $video->thumbnail_path
                                        ) }}" alt="{{ $video->title }}">

                                    @else

                                        <div class="default-thumbnail">

                                            <i class="bi bi-play-circle-fill"></i>

                                        </div>

                                    @endif


                                    <span class="video-duration">
                                        {{ $video->duration
                            ? gmdate(
                                'H:i:s',
                                $video->duration
                            )
                            : 'NEW'
                                                                                }}
                                    </span>


                                    <div class="thumbnail-overlay">

                                        <i class="bi bi-play-fill"></i>

                                    </div>

                                </div>


                                {{-- Video Info --}}

                                <div class="video-info">

                                    <div class="channel-avatar">

                                        @if($video->channel->avatar)

                                                        <img src="{{ asset(
                                                'storage/' .
                                                $video->channel->avatar
                                            ) }}" alt="">

                                        @else

                                                        {{ strtoupper(
                                                substr(
                                                    $video->channel->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        @endif

                                    </div>


                                    <div class="video-text">

                                        <h3>

                                            {{ $video->title }}

                                        </h3>


                                        <p class="channel-name">

                                            {{ $video->channel->name }}

                                            @if(
                                                    $video->channel->is_verified
                                                )

                                                <i class="bi bi-patch-check-fill verified"></i>

                                            @endif

                                        </p>


                                        <p class="video-meta">

                                            {{ number_format(
                            $video->views_count
                        ) }}

                                            views

                                            <span>•</span>

                                            {{ $video->published_at
                            ? $video->published_at
                                ->diffForHumans()
                            : 'Recently'
                                                                                    }}

                                        </p>

                                    </div>


                                    <button type="button" class="video-menu" onclick="event.preventDefault()">

                                        <i class="bi bi-three-dots-vertical"></i>

                                    </button>

                                </div>

                            </a>

                    @endforeach

                </div>


                {{-- Pagination --}}

                <div class="tradim-pagination">

                    {{ $videos->links() }}

                </div>


            @else

                <div class="empty-videos">

                    <div class="empty-icon">

                        <i class="bi bi-camera-video-off"></i>

                    </div>


                    <h3>
                        No videos yet
                    </h3>


                    <p>
                        Be the first creator to upload a video on Tradim.
                    </p>


                    @auth

                        <a href="{{ route('videos.create') }}" class="btn-tradim">

                            Upload First Video

                        </a>

                    @endauth

                </div>

            @endif

        </section>

    </div>


    <style>
        /* =========================================================
               TRADIM HOME
            ========================================================= */

        .tradim-home {

            color: #f8fafc;

        }


        /* =========================================================
               CATEGORY BAR
            ========================================================= */

        .category-scroll {

            display: flex;

            gap: 10px;

            overflow-x: auto;

            padding: 5px 0 20px;

            scrollbar-width: none;

        }

        .category-scroll::-webkit-scrollbar {

            display: none;

        }


        .category-pill {

            flex: 0 0 auto;

            border: 1px solid #263047;

            background: #141a2a;

            color: #cbd5e1;

            padding: 9px 16px;

            border-radius: 30px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition: .2s;

        }


        .category-pill:hover {

            color: #ffffff;

            border-color: #7c3aed;

            background: #1c1732;

        }


        .category-pill.active {

            color: #ffffff;

            border-color: #7c3aed;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

        }


        /* =========================================================
               HERO
            ========================================================= */

        .tradim-hero {

            min-height: 310px;

            border-radius: 24px;

            padding: 55px;

            margin-bottom: 45px;

            position: relative;

            overflow: hidden;

            background:
                radial-gradient(circle at 85% 30%,
                    rgba(236, 72, 153, .25),
                    transparent 35%),
                radial-gradient(circle at 65% 70%,
                    rgba(124, 58, 237, .25),
                    transparent 40%),
                #101728;

            border: 1px solid #273149;

        }


        .hero-content {

            position: relative;

            z-index: 2;

            max-width: 650px;

        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 7px 13px;

            border-radius: 20px;

            color: #d8b4fe;

            background: rgba(124, 58, 237, .15);

            border: 1px solid rgba(167, 139, 250, .25);

            font-size: 12px;

            font-weight: 800;

            letter-spacing: .7px;

        }


        .tradim-hero h1 {

            margin-top: 18px;

            font-size: clamp(38px, 5vw, 64px);

            line-height: 1.05;

            font-weight: 900;

            color: #ffffff;

        }


        .tradim-hero h1 span {

            background: linear-gradient(90deg,
                    #a78bfa,
                    #f472b6);

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;

        }


        .tradim-hero p {

            color: #aeb9cc;

            font-size: 17px;

            line-height: 1.7;

            max-width: 590px;

        }


        .hero-buttons {

            display: flex;

            gap: 12px;

            margin-top: 25px;

        }


        .btn-tradim {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            border: 0;

            border-radius: 10px;

            padding: 12px 20px;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: #ffffff !important;

            text-decoration: none;

            font-weight: 700;

            transition: .2s;

        }


        .btn-tradim:hover {

            color: #ffffff !important;

            transform: translateY(-1px);

        }


        .btn-tradim-outline {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            border: 1px solid #39445d;

            border-radius: 10px;

            padding: 11px 20px;

            background: #151c2d;

            color: #e2e8f0 !important;

            text-decoration: none;

            font-weight: 700;

        }


        .btn-tradim-outline:hover {

            color: #ffffff !important;

            border-color: #7c3aed;

        }


        /* =========================================================
               HERO PLAY
            ========================================================= */

        .hero-decoration {

            position: absolute;

            right: 10%;

            top: 50%;

            transform: translateY(-50%);

        }


        .hero-play {

            width: 150px;

            height: 150px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 65px;

            color: #ffffff;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            box-shadow:
                0 0 80px rgba(124, 58, 237, .4);

        }


        /* =========================================================
               SECTION
            ========================================================= */

        .section-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

        }


        .section-header h2 {

            color: #ffffff;

            font-size: 28px;

            font-weight: 800;

            margin-bottom: 5px;

        }


        .section-header p {

            color: #8491a7;

            margin: 0;

        }


        /* =========================================================
               VIDEO GRID
            ========================================================= */

        .video-grid {

            display: grid;

            grid-template-columns:
                repeat(4,
                    minmax(0, 1fr));

            gap: 28px 18px;

        }


        .video-card {

            color: inherit;

            text-decoration: none;

            min-width: 0;

        }


        .video-thumbnail {

            height: 190px;

            position: relative;

            overflow: hidden;

            border-radius: 12px;

            background: #151c2d;

        }


        .video-thumbnail img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            display: block;

            transition: transform .35s;

        }


        .video-card:hover .video-thumbnail img {

            transform: scale(1.04);

        }


        .default-thumbnail {

            width: 100%;

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            color: #8b5cf6;

            font-size: 50px;

            background:
                linear-gradient(135deg,
                    #171d31,
                    #252044);

        }


        .video-duration {

            position: absolute;

            right: 8px;

            bottom: 8px;

            padding: 4px 7px;

            border-radius: 5px;

            background: rgba(0, 0, 0, .8);

            color: #ffffff;

            font-size: 11px;

            font-weight: 700;

        }


        .thumbnail-overlay {

            position: absolute;

            inset: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            opacity: 0;

            background: rgba(0, 0, 0, .35);

            transition: .2s;

        }


        .thumbnail-overlay i {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: rgba(124, 58, 237, .95);

            color: #ffffff;

            font-size: 28px;

        }


        .video-card:hover .thumbnail-overlay {

            opacity: 1;

        }


        /* =========================================================
               VIDEO INFO
            ========================================================= */

        .video-info {

            display: flex;

            gap: 11px;

            padding-top: 12px;

            position: relative;

        }


        .channel-avatar {

            flex: 0 0 38px;

            width: 38px;

            height: 38px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            overflow: hidden;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: #ffffff;

            font-size: 15px;

            font-weight: 800;

        }


        .channel-avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }


        .video-text {

            min-width: 0;

            padding-right: 25px;

        }


        .video-text h3 {

            color: #f8fafc;

            font-size: 15px;

            line-height: 1.4;

            font-weight: 700;

            margin: 0 0 6px;

            display: -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;

        }


        .channel-name {

            color: #94a3b8;

            font-size: 13px;

            margin: 0 0 3px;

        }


        .video-meta {

            color: #64748b;

            font-size: 12px;

            margin: 0;

        }


        .video-meta span {

            margin: 0 4px;

        }


        .verified {

            color: #60a5fa;

        }


        .video-menu {

            position: absolute;

            right: 0;

            top: 10px;

            background: transparent;

            border: 0;

            color: #64748b;

            font-size: 18px;

        }


        .video-menu:hover {

            color: #ffffff;

        }


        /* =========================================================
               EMPTY
            ========================================================= */

        .empty-videos {

            text-align: center;

            padding: 80px 20px;

            border-radius: 18px;

            background: #101728;

            border: 1px solid #273149;

        }


        .empty-icon {

            width: 80px;

            height: 80px;

            margin: 0 auto 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #1d2540;

            color: #8b5cf6;

            font-size: 35px;

        }


        .empty-videos h3 {

            color: #ffffff;

        }


        .empty-videos p {

            color: #94a3b8;

        }


        /* =========================================================
               PAGINATION
            ========================================================= */

        .tradim-pagination {

            margin-top: 35px;

        }


        .tradim-pagination nav {

            display: flex;

            justify-content: center;

        }


        .tradim-pagination .pagination {

            gap: 6px;

        }


        .tradim-pagination .page-link {

            background: #151c2d;

            border: 1px solid #2b354c;

            color: #cbd5e1;

            border-radius: 8px;

        }


        .tradim-pagination .page-item.active .page-link {

            background: #7c3aed;

            border-color: #7c3aed;

            color: #ffffff;

        }


        .tradim-pagination .page-item.disabled .page-link {

            background: #101624;

            color: #475569;

        }


        /* =========================================================
               RESPONSIVE
            ========================================================= */

        @media (max-width: 1200px) {

            .video-grid {

                grid-template-columns:
                    repeat(3, minmax(0, 1fr));

            }

        }


        @media (max-width: 900px) {

            .video-grid {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }


            .hero-decoration {

                display: none;

            }

        }


        @media (max-width: 600px) {

            .tradim-hero {

                padding: 30px 22px;

            }


            .tradim-hero h1 {

                font-size: 38px;

            }


            .hero-buttons {

                flex-direction: column;

            }


            .video-grid {

                grid-template-columns: 1fr;

            }


            .video-thumbnail {

                height: 220px;

            }

        }
    </style>

@endsection