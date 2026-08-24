@extends('layouts.app')

@section('title', 'Tradim - Watch. Create. Connect.')

@section('content')

    <div class="tradim-home">


        {{-- =========================================================
             HERO
        ========================================================== --}}

        <section class="tradim-hero">

            <div class="hero-content">

                <span class="hero-badge">

                    <i class="bi bi-stars"></i>

                    Welcome to Tradim

                </span>


                <h1>

                    Watch what you love.
                    <br>

                    <span>Create what matters.</span>

                </h1>


                <p>

                    Discover videos, creators and communities
                    from around the world.

                </p>


                <div class="hero-actions">

                    <a
                        href="#videos"
                        class="hero-primary"
                    >

                        <i class="bi bi-play-fill"></i>

                        Explore Videos

                    </a>


                    @auth

                        <a
                            href="{{ route('creator.dashboard') }}"
                            class="hero-secondary"
                        >

                            <i class="bi bi-camera-video"></i>

                            Create

                        </a>

                    @else

                        <a
                            href="{{ route('register') }}"
                            class="hero-secondary"
                        >

                            <i class="bi bi-person-plus"></i>

                            Join Tradim

                        </a>

                    @endauth

                </div>

            </div>

        </section>



        {{-- =========================================================
             CATEGORIES
        ========================================================== --}}

        <section class="category-section">

            <div class="section-heading">

                <div>

                    <h2>

                        Explore

                    </h2>

                    <p>

                        Find something you love

                    </p>

                </div>

            </div>


            <div class="category-scroll">

                @foreach($categories as $category)

                                <a
                                    href="{{ route(
                        'home'
                    ) }}?category={{ $category->id }}"
                                    class="category-pill"
                                >

                                    <i class="bi bi-grid"></i>

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

                            What's popular on Tradim

                        </p>

                    </div>


                    <a
                        href="#videos"
                        class="view-all"
                    >

                        View all

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>


                <div class="row g-4">

                    @foreach(
                            $trendingVideos->take(4)
                            as $video
                        )

                            <div
                                class="col-xl-3
                                       col-lg-4
                                       col-md-6"
                            >

                                @include(
                                    'videos.partials.card',
                                    [
                                        'video' => $video
                                    ]
                                )

                            </div>

                    @endforeach

                </div>

            </section>

        @endif



        {{-- =========================================================
             LATEST VIDEOS
        ========================================================== --}}

        <section
            class="video-section"
            id="videos"
        >

            <div class="section-heading">

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

                <div class="row g-4">

                    @foreach($videos as $video)

                        <div
                            class="col-xl-3
                                   col-lg-4
                                   col-md-6"
                        >

                            @include(
                                'videos.partials.card',
                                [
                                    'video' => $video
                                ]
                            )

                        </div>

                    @endforeach

                </div>


                {{-- PAGINATION --}}

                <div class="tradim-pagination">

                    {{ $videos->links() }}

                </div>

            @else

                <div class="empty-home">

                    <div class="empty-icon">

                        <i class="bi bi-camera-video-off"></i>

                    </div>


                    <h3>

                        No videos yet

                    </h3>


                    <p>

                        Be the first creator to upload
                        a video on Tradim.

                    </p>


                    @auth

                                    <a
                                        href="{{ route(
                            'creator.videos.create'
                        ) }}"
                                        class="hero-primary"
                                    >

                                        Upload Video

                                    </a>

                    @endauth

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

    }


    /* =========================================================
       HERO
    ========================================================= */

    .tradim-hero {

        position: relative;

        min-height: 330px;

        display: flex;

        align-items: center;

        padding: 55px 45px;

        margin-bottom: 45px;

        overflow: hidden;

        border-radius: 20px;

        background:
            radial-gradient(
                circle at 80% 20%,
                rgba(124,58,237,.25),
                transparent 35%
            ),
            radial-gradient(
                circle at 20% 80%,
                rgba(236,72,153,.15),
                transparent 35%
            ),
            #0e1525;

        border: 1px solid #273149;

    }


    .tradim-hero::after {

        content: "";

        position: absolute;

        width: 300px;

        height: 300px;

        right: -100px;

        top: -100px;

        border-radius: 50%;

        border: 1px solid rgba(
            139,
            92,
            246,
            .25
        );

    }


    .hero-content {

        position: relative;

        z-index: 2;

        max-width: 700px;

    }


    .hero-badge {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 7px 13px;

        margin-bottom: 18px;

        border-radius: 30px;

        background: rgba(
            124,
            58,
            237,
            .15
        );

        border: 1px solid rgba(
            124,
            58,
            237,
            .3
        );

        color: #c4b5fd;

        font-size: 12px;

        font-weight: 700;

    }


    .hero-content h1 {

        color: #ffffff;

        font-size: 44px;

        line-height: 1.12;

        font-weight: 900;

        margin: 0 0 15px;

    }


    .hero-content h1 span {

        background: linear-gradient(
            90deg,
            #a78bfa,
            #ec4899
        );

        -webkit-background-clip: text;

        -webkit-text-fill-color: transparent;

    }


    .hero-content p {

        color: #94a3b8;

        font-size: 15px;

        line-height: 1.7;

        max-width: 580px;

        margin-bottom: 25px;

    }


    .hero-actions {

        display: flex;

        gap: 10px;

        flex-wrap: wrap;

    }


    .hero-primary,
    .hero-secondary {

        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 11px 18px;

        border-radius: 10px;

        text-decoration: none;

        font-size: 14px;

        font-weight: 700;

    }


    .hero-primary {

        background: #7c3aed;

        color: #ffffff !important;

    }


    .hero-primary:hover {

        background: #6d28d9;

        color: #ffffff !important;

    }


    .hero-secondary {

        background: #1a2335;

        color: #e2e8f0 !important;

        border: 1px solid #334155;

    }


    .hero-secondary:hover {

        border-color: #7c3aed;

    }


    /* =========================================================
       SECTIONS
    ========================================================= */

    .video-section,
    .category-section {

        margin-bottom: 45px;

    }


    .section-heading {

        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 20px;

    }


    .section-heading h2 {

        color: #ffffff;

        font-size: 22px;

        font-weight: 800;

        margin: 0 0 4px;

    }


    .section-heading h2 i {

        color: #f97316;

    }


    .section-heading p {

        color: #64748b;

        font-size: 12px;

        margin: 0;

    }


    .view-all {

        color: #a78bfa;

        text-decoration: none;

        font-size: 13px;

        font-weight: 700;

    }


    .view-all:hover {

        color: #c4b5fd;

    }


    /* =========================================================
       CATEGORY
    ========================================================= */

    .category-scroll {

        display: flex;

        gap: 10px;

        overflow-x: auto;

        padding-bottom: 5px;

    }


    .category-scroll::-webkit-scrollbar {

        height: 4px;

    }


    .category-pill {

        flex: 0 0 auto;

        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 9px 15px;

        border-radius: 25px;

        background: #151d2e;

        border: 1px solid #273149;

        color: #cbd5e1;

        text-decoration: none;

        font-size: 12px;

        font-weight: 600;

    }


    .category-pill:hover {

        color: #ffffff;

        border-color: #7c3aed;

        background: #1c2540;

    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .tradim-pagination {

        display: flex;

        justify-content: center;

        margin-top: 35px;

    }


    .tradim-pagination nav {

        display: flex;

        justify-content: center;

    }


    .tradim-pagination svg {

        width: 18px;

        height: 18px;

    }


    .tradim-pagination span,
    .tradim-pagination a {

        color: #cbd5e1 !important;

    }


    .tradim-pagination [aria-current="page"] span {

        background: #7c3aed !important;

        border-color: #7c3aed !important;

        color: #ffffff !important;

    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .empty-home {

        text-align: center;

        padding: 70px 20px;

        background: #111a2b;

        border: 1px solid #273149;

        border-radius: 16px;

    }


    .empty-icon {

        width: 70px;

        height: 70px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin: auto;

        border-radius: 50%;

        background: #1c2540;

        color: #8b5cf6;

        font-size: 30px;

    }


    .empty-home h3 {

        color: #ffffff;

        margin: 18px 0 7px;

    }


    .empty-home p {

        color: #64748b;

        margin-bottom: 20px;

    }


    @media (max-width: 768px) {

        .tradim-hero {

            padding: 35px 25px;

        }


        .hero-content h1 {

            font-size: 32px;

        }

    }

    </style>

@endsection