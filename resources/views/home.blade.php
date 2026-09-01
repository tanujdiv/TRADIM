@extends('layouts.app')

@section('title', 'Home - Tradim')

@section('content')

    <div class="tradim-home">

        {{-- =========================================================
        HERO
        ========================================================== --}}

        <section class="tradim-hero">

            <div class="hero-content">

                <span class="hero-badge">
                    <i class="bi bi-play-circle-fill"></i>
                    Welcome to Tradim
                </span>

                <h1>
                    Watch.
                    <span>Discover.</span>
                    Create.
                </h1>

                <p>
                    Discover amazing videos, follow your favorite creators
                    and share your own content with the world.
                </p>

                <div class="hero-actions">

                    <a href="#videos" class="btn-tradim">
                        <i class="bi bi-play-fill"></i>
                        Explore Videos
                    </a>

                    @auth

                        <a href="{{ route('videos.create') }}" class="btn-tradim-outline">
                            <i class="bi bi-cloud-upload"></i>
                            Upload Video
                        </a>

                    @else

                        <a href="{{ route('register') }}" class="btn-tradim-outline">
                            <i class="bi bi-person-plus"></i>
                            Join Tradim
                        </a>

                    @endauth

                </div>

            </div>

            <div class="hero-decoration">

                <div class="hero-circle hero-circle-one"></div>
                <div class="hero-circle hero-circle-two"></div>

                <i class="bi bi-play-fill hero-play-icon"></i>

            </div>

        </section>


        {{-- =========================================================
        CATEGORY BAR
        ========================================================== --}}

        <section class="category-section">

            <div class="category-scroll">

                <a href="{{ route('home') }}" class="category-pill {{ !$categoryId ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    All
                </a>

                @foreach($categories as $category)

                    <a href="{{ route('home', ['category' => $category->id]) }}"
                        class="category-pill {{ (int) $categoryId === (int) $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>

                @endforeach

            </div>

        </section>


        {{-- =========================================================
        TRENDING
        ========================================================== --}}

        @if($trendingVideos->count())

            <section class="video-section">

                <div class="section-heading">

                    <div>
                        <h2>
                            <i class="bi bi-fire"></i>
                            Trending
                        </h2>

                        <p>
                            Videos people are watching right now
                        </p>
                    </div>

                </div>


                <div class="video-grid">

                    @foreach($trendingVideos as $video)

                        @include('videos.partials.card', [
                            'video' => $video
                        ])

                    @endforeach

                </div>

            </section>

        @endif


        {{-- =========================================================
        POPULAR
        ========================================================== --}}

        @if($popularVideos->count())

            <section class="video-section">

                <div class="section-heading">

                    <div>
                        <h2>
                            <i class="bi bi-star-fill"></i>
                            Popular
                        </h2>

                        <p>
                            Popular videos from the Tradim community
                        </p>
                    </div>

                </div>


                <div class="video-grid">

                    @foreach($popularVideos as $video)

                        @include('videos.partials.card', [
                            'video' => $video
                        ])

                    @endforeach

                </div>

            </section>

        @endif


        {{-- =========================================================
        LATEST VIDEOS
        ========================================================== --}}

        <section class="video-section" id="videos">

            <div class="section-heading">

                <div>

                    <h2>

                        @if($categoryId)

                            <i class="bi bi-funnel-fill"></i>
                            Category Videos

                        @else

                            <i class="bi bi-clock-fill"></i>
                            Latest Videos

                        @endif

                    </h2>

                    <p>
                        Fresh content from Tradim creators
                    </p>

                </div>

            </div>


            @if($latestVideos->count())

                <div class="video-grid">

                    @foreach($latestVideos as $video)

                        @include('videos.partials.card', [
                            'video' => $video
                        ])

                    @endforeach

                </div>


                {{-- PAGINATION --}}

                <div class="tradim-pagination">

                    {{ $latestVideos->links() }}

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-camera-video-off"></i>
                    </div>

                    <h3>
                        No videos found
                    </h3>

                    <p>
                        There are no videos available in this category yet.
                    </p>

                    <a href="{{ route('home') }}" class="btn-tradim">
                        View All Videos
                    </a>

                </div>

            @endif

        </section>

    </div>


    <style>
        /* =========================================================
       HOME
    ========================================================= */

        .tradim-home {
            color: #f8fafc;
            padding-bottom: 50px;
        }


        /* =========================================================
       HERO
    ========================================================= */

        .tradim-hero {
            position: relative;
            min-height: 340px;

            display: flex;
            align-items: center;

            padding: 50px;

            margin-bottom: 30px;

            overflow: hidden;

            border-radius: 22px;

            background:
                radial-gradient(circle at 85% 30%,
                    rgba(124, 58, 237, .28),
                    transparent 35%),
                radial-gradient(circle at 70% 90%,
                    rgba(236, 72, 153, .16),
                    transparent 35%),
                #0d1425;

            border: 1px solid #202b42;
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

            background: rgba(124, 58, 237, .14);

            border: 1px solid rgba(124, 58, 237, .35);

            color: #c4b5fd;

            font-size: 12px;
            font-weight: 700;
        }


        .hero-content h1 {
            margin: 18px 0 12px;

            color: #ffffff;

            font-size: clamp(34px, 5vw, 58px);

            line-height: 1.05;

            font-weight: 900;

            letter-spacing: -1.5px;
        }


        .hero-content h1 span {
            color: #a78bfa;
        }


        .hero-content p {
            max-width: 570px;

            color: #94a3b8;

            font-size: 15px;

            line-height: 1.7;

            margin-bottom: 25px;
        }


        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }


        .btn-tradim,
        .btn-tradim-outline {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 11px 18px;

            border-radius: 10px;

            text-decoration: none;

            font-size: 13px;

            font-weight: 700;

            transition: .2s;
        }


        .btn-tradim {
            background: #7c3aed;

            color: #ffffff !important;

            border: 1px solid #7c3aed;
        }


        .btn-tradim:hover {
            background: #6d28d9;

            color: #ffffff !important;

            transform: translateY(-1px);
        }


        .btn-tradim-outline {
            background: rgba(255, 255, 255, .03);

            color: #e2e8f0 !important;

            border: 1px solid #334155;
        }


        .btn-tradim-outline:hover {
            background: rgba(255, 255, 255, .07);

            color: #ffffff !important;

            border-color: #7c3aed;
        }


        /* =========================================================
       HERO DECORATION
    ========================================================= */

        .hero-decoration {
            position: absolute;

            right: 8%;

            width: 220px;
            height: 220px;

            display: flex;

            align-items: center;
            justify-content: center;
        }


        .hero-circle {
            position: absolute;

            border-radius: 50%;

            border: 1px solid rgba(167, 139, 250, .18);
        }


        .hero-circle-one {
            width: 210px;
            height: 210px;
        }


        .hero-circle-two {
            width: 150px;
            height: 150px;
        }


        .hero-play-icon {
            position: relative;

            z-index: 3;

            width: 75px;
            height: 75px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: rgba(124, 58, 237, .2);

            border: 1px solid rgba(167, 139, 250, .4);

            color: #c4b5fd;

            font-size: 35px;

            padding-left: 4px;
        }


        /* =========================================================
       CATEGORIES
    ========================================================= */

        .category-section {
            margin-bottom: 32px;
        }


        .category-scroll {
            display: flex;

            gap: 9px;

            overflow-x: auto;

            padding-bottom: 5px;

            scrollbar-width: none;
        }


        .category-scroll::-webkit-scrollbar {
            display: none;
        }


        .category-pill {
            flex: 0 0 auto;

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 9px 15px;

            border-radius: 22px;

            background: #111a2c;

            border: 1px solid #263149;

            color: #94a3b8 !important;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            transition: .2s;
        }


        .category-pill:hover {
            color: #ffffff !important;

            border-color: #7c3aed;
        }


        .category-pill.active {
            background: #7c3aed;

            border-color: #7c3aed;

            color: #ffffff !important;
        }


        /* =========================================================
       VIDEO SECTION
    ========================================================= */

        .video-section {
            margin-bottom: 45px;
        }


        .section-heading {
            display: flex;

            align-items: center;
            justify-content: space-between;

            margin-bottom: 20px;
        }


        .section-heading h2 {
            display: flex;

            align-items: center;

            gap: 9px;

            color: #ffffff;

            font-size: 21px;

            font-weight: 800;

            margin: 0 0 5px;
        }


        .section-heading h2 i {
            color: #a78bfa;
        }


        .section-heading p {
            color: #64748b;

            font-size: 12px;

            margin: 0;
        }


        /* =========================================================
       VIDEO GRID
    ========================================================= */

        .video-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 22px 17px;
        }


        /* =========================================================
       PAGINATION
    ========================================================= */

        .tradim-pagination {
            display: flex;

            justify-content: center;

            margin-top: 30px;
        }


        .tradim-pagination nav {
            display: flex;
        }


        .tradim-pagination .pagination {
            margin: 0;

            gap: 5px;
        }


        .tradim-pagination .page-link {
            background: #111a2c;

            border: 1px solid #29344b;

            color: #cbd5e1;

            border-radius: 8px;
        }


        .tradim-pagination .page-link:hover {
            background: #1d2940;

            color: #ffffff;
        }


        .tradim-pagination .active .page-link {
            background: #7c3aed;

            border-color: #7c3aed;

            color: #ffffff;
        }


        .tradim-pagination .disabled .page-link {
            background: #0d1425;

            color: #475569;
        }


        /* =========================================================
       EMPTY STATE
    ========================================================= */

        .empty-state {
            padding: 60px 20px;

            text-align: center;

            background: #0d1425;

            border: 1px solid #202b42;

            border-radius: 16px;
        }


        .empty-icon {
            width: 65px;
            height: 65px;

            margin: 0 auto 15px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #171f34;

            color: #8b5cf6;

            font-size: 27px;
        }


        .empty-state h3 {
            color: #ffffff;

            font-size: 18px;

            margin-bottom: 7px;
        }


        .empty-state p {
            color: #64748b;

            font-size: 13px;

            margin-bottom: 20px;
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

            .tradim-hero {
                padding: 35px;
            }

            .hero-decoration {
                right: -50px;
                opacity: .4;
            }

            .video-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 600px) {

            .tradim-hero {
                min-height: 300px;

                padding: 25px 20px;

                border-radius: 16px;
            }

            .hero-content h1 {
                font-size: 35px;
            }

            .hero-decoration {
                display: none;
            }

            .video-grid {
                grid-template-columns:
                    1fr;

                gap: 25px;
            }

            .section-heading h2 {
                font-size: 18px;
            }

        }
    </style>

@endsection