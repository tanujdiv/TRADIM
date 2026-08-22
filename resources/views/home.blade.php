@extends('layouts.app')

@section('title', 'Tradim - Watch, Create & Connect')


@section('content')

    <div class="home-container">


        <!-- =====================================================
             HERO
        ====================================================== -->

        <section class="hero-banner">


            <div class="hero-content">

                <span class="hero-badge">

                    <i class="bi bi-stars"></i>

                    THE NEXT GENERATION VIDEO PLATFORM

                </span>


                <h1 class="hero-title">

                    Watch.

                    Create.

                    <br>

                    <span class="gradient-text">
                        Connect.
                    </span>

                </h1>


                <p class="hero-description">

                    Tradim is an all-in-one video platform where
                    creators and viewers come together.

                    Watch videos, discover Shorts, join live streams,
                    listen to podcasts and explore AI-powered features.

                </p>


                <div class="hero-buttons">

                    <a href="#" class="btn-tradim">

                        <i class="bi bi-play-fill"></i>

                        Explore Videos

                    </a>


                    <a href="#" class="btn-tradim-outline">

                        <i class="bi bi-camera-video"></i>

                        Start Creating

                    </a>

                </div>

            </div>


            <!-- HERO VISUAL -->

            <div class="hero-visual">


                <div class="hero-screen">

                    <div class="hero-screen-top"></div>

                    <div class="hero-screen-content"></div>

                </div>


                <div class="hero-phone">

                    <div class="hero-phone-content"></div>

                </div>


            </div>

        </section>



        <!-- =====================================================
             CATEGORIES
        ====================================================== -->

        <div class="category-scroll">


            <button class="category-btn active">
                All
            </button>


            <button class="category-btn">
                Music
            </button>


            <button class="category-btn">
                Gaming
            </button>


            <button class="category-btn">
                Technology
            </button>


            <button class="category-btn">
                Education
            </button>


            <button class="category-btn">
                Entertainment
            </button>


            <button class="category-btn">
                Sports
            </button>


            <button class="category-btn">
                News
            </button>


            <button class="category-btn">
                Podcasts
            </button>


            <button class="category-btn">
                AI
            </button>


            <button class="category-btn">
                Travel
            </button>


        </div>



        <!-- =====================================================
             RECOMMENDED
        ====================================================== -->

        <section class="content-section">


            <div class="section-header">

                <div>

                    <h2 class="section-title">
                        Recommended for you
                    </h2>

                    <div class="section-subtitle">
                        Videos selected for your interests
                    </div>

                </div>


                <a href="#" class="view-all">
                    View All
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>



            <div class="row g-4">


                <!-- VIDEO 1 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #0f172a,
                                        #1d4ed8,
                                        #06b6d4
                                    );
                                "></div>


                            <span class="video-duration">
                                12:45
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                T
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Build Modern Web Applications
                                    With Laravel 13

                                </h3>


                                <div class="channel-name">
                                    Tradim Tech
                                </div>


                                <div class="video-meta">

                                    125K views • 2 days ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 2 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #312e81,
                                        #7c3aed,
                                        #ec4899
                                    );
                                "></div>


                            <span class="video-duration">
                                18:21
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                C
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Complete Creator Setup
                                    For 2026

                                </h3>


                                <div class="channel-name">
                                    Creator World
                                </div>


                                <div class="video-meta">

                                    84K views • 1 week ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 3 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #052e16,
                                        #16a34a,
                                        #22c55e
                                    );
                                "></div>


                            <span class="video-duration">
                                09:18
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                A
                            </div>


                            <div>

                                <h3 class="video-title">

                                    AI Tools That Will Change
                                    Your Workflow

                                </h3>


                                <div class="channel-name">
                                    AI Central
                                </div>


                                <div class="video-meta">

                                    242K views • 3 days ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 4 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #431407,
                                        #ea580c,
                                        #f59e0b
                                    );
                                "></div>


                            <span class="video-duration">
                                24:50
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                T
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Amazing Places You Need
                                    To Visit

                                </h3>


                                <div class="channel-name">
                                    Travel Stories
                                </div>


                                <div class="video-meta">

                                    1.2M views • 5 days ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 5 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #111827,
                                        #475569,
                                        #94a3b8
                                    );
                                "></div>


                            <span class="video-duration">
                                15:42
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                G
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Ultimate Gaming Setup
                                    Guide

                                </h3>


                                <div class="channel-name">
                                    GameZone
                                </div>


                                <div class="video-meta">

                                    560K views • 4 days ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 6 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #164e63,
                                        #0891b2,
                                        #06b6d4
                                    );
                                "></div>


                            <span class="video-duration">
                                21:08
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                E
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Learn Programming
                                    From Zero

                                </h3>


                                <div class="channel-name">
                                    EasyCode
                                </div>


                                <div class="video-meta">

                                    312K views • 1 day ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 7 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #3f0d2e,
                                        #be185d,
                                        #f43f5e
                                    );
                                "></div>


                            <span class="video-duration">
                                11:32
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                M
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Music That Everyone
                                    Is Listening To

                                </h3>


                                <div class="channel-name">
                                    Music Daily
                                </div>


                                <div class="video-meta">

                                    890K views • 6 hours ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>



                <!-- VIDEO 8 -->

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <article class="video-card">


                        <div class="video-thumbnail">

                            <div style="
                                    width:100%;
                                    height:100%;
                                    background:
                                    linear-gradient(
                                        135deg,
                                        #172554,
                                        #4338ca,
                                        #6366f1
                                    );
                                "></div>


                            <span class="video-duration">
                                17:05
                            </span>

                        </div>


                        <div class="video-info">

                            <div class="channel-avatar">
                                D
                            </div>


                            <div>

                                <h3 class="video-title">

                                    Future Of Technology
                                    And AI

                                </h3>


                                <div class="channel-name">
                                    Digital Future
                                </div>


                                <div class="video-meta">

                                    475K views • 2 weeks ago

                                </div>

                            </div>

                        </div>


                    </article>

                </div>


            </div>

        </section>



        <!-- =====================================================
             SHORTS
        ====================================================== -->

        <section class="shorts-section">


            <div class="section-header">

                <div>

                    <h2 class="section-title">

                        <i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i>

                        Shorts

                    </h2>

                    <div class="section-subtitle">
                        Quick videos. Endless discovery.
                    </div>

                </div>


                <a href="#" class="view-all">
                    View All
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>



            <div class="row g-3">


                @php

                    $shorts = [

                        [
                            'title' => 'This place looks unreal 😍',
                            'views' => '2.4M views',
                        ],

                        [
                            'title' => 'You need to know this AI trick 🤖',
                            'views' => '890K views',
                        ],

                        [
                            'title' => 'Wait for the ending 😂',
                            'views' => '4.1M views',
                        ],

                        [
                            'title' => 'The best setup for creators 🔥',
                            'views' => '1.2M views',
                        ],

                        [
                            'title' => 'This coding trick is crazy ⚡',
                            'views' => '756K views',
                        ],

                        [
                            'title' => 'Amazing transformation ❤️',
                            'views' => '3.8M views',
                        ],

                    ];

                @endphp


                @foreach($shorts as $short)

                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">

                        <article class="short-card">

                            <div style="
                                        position:absolute;
                                        inset:0;
                                        background:
                                        linear-gradient(
                                            160deg,
                                            #172554,
                                            #7c3aed,
                                            #be185d
                                        );
                                    "></div>


                            <div class="short-card-content">

                                <div>

                                    <div class="short-title">
                                        {{ $short['title'] }}
                                    </div>

                                    <div class="short-views">
                                        {{ $short['views'] }}
                                    </div>

                                </div>

                            </div>

                        </article>

                    </div>

                @endforeach


            </div>

        </section>



        <!-- =====================================================
             LIVE
        ====================================================== -->

        <section class="content-section mt-5">


            <div class="section-header">

                <div>

                    <h2 class="section-title">

                        <i class="bi bi-broadcast-pin" style="color:#ef4444;"></i>

                        Live Now

                    </h2>

                    <div class="section-subtitle">
                        Join creators who are streaming right now.
                    </div>

                </div>


                <a href="#" class="view-all">
                    See All
                </a>

            </div>



            <div class="row g-4">


                @foreach([
                        ['name' => 'Tech Live', 'viewers' => '12.4K'],
                        ['name' => 'Gaming Arena', 'viewers' => '8.7K'],
                        ['name' => 'Music Night', 'viewers' => '5.2K'],
                        ['name' => 'Creator Talk', 'viewers' => '3.8K']
                    ] as $live)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <article class="video-card">

                            <div class="video-thumbnail">

                                <div style="
                                            width:100%;
                                            height:100%;
                                            background:
                                            linear-gradient(
                                                135deg,
                                                #111827,
                                                #7f1d1d,
                                                #dc2626
                                            );
                                        "></div>


                                <span class="video-live">

                                    <i class="bi bi-broadcast"></i>

                                    LIVE

                                </span>


                                <span class="video-duration">

                                    {{ $live['viewers'] }}
                                    watching

                                </span>

                            </div>


                            <div class="video-info">

                                <div class="channel-avatar">
                                    L
                                </div>


                                <div>

                                    <h3 class="video-title">
                                        {{ $live['name'] }}
                                    </h3>

                                    <div class="channel-name">
                                        Tradim Live
                                    </div>

                                    <div class="video-meta">
                                        Live now
                                    </div>

                                </div>

                            </div>

                        </article>

                    </div>

                @endforeach


            </div>

        </section>



        <!-- =====================================================
             TRADIM FEATURES
        ====================================================== -->

        <section class="feature-strip">


            <div class="row g-4">


                <div class="col-lg-3 col-md-6">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <i class="bi bi-robot"></i>

                        </div>

                        <div>

                            <h6>
                                AI Powered
                            </h6>

                            <p>
                                Smart recommendations and tools.
                            </p>

                        </div>

                    </div>

                </div>



                <div class="col-lg-3 col-md-6">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <i class="bi bi-broadcast"></i>

                        </div>

                        <div>

                            <h6>
                                Live Streaming
                            </h6>

                            <p>
                                Connect with your audience live.
                            </p>

                        </div>

                    </div>

                </div>



                <div class="col-lg-3 col-md-6">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <i class="bi bi-bar-chart"></i>

                        </div>

                        <div>

                            <h6>
                                Creator Analytics
                            </h6>

                            <p>
                                Understand your audience.
                            </p>

                        </div>

                    </div>

                </div>



                <div class="col-lg-3 col-md-6">

                    <div class="feature-item">

                        <div class="feature-icon">

                            <i class="bi bi-currency-dollar"></i>

                        </div>

                        <div>

                            <h6>
                                Monetization
                            </h6>

                            <p>
                                Multiple ways to earn.
                            </p>

                        </div>

                    </div>

                </div>


            </div>

        </section>


    </div>

@endsection



@push('scripts')

    <script>

        const sidebarToggle =
            document.getElementById('sidebarToggle');

        const sidebar =
            document.getElementById('tradimSidebar');


        sidebarToggle.addEventListener(
            'click',
            function () {

                sidebar.classList.toggle('open');

            }
        );


        document.addEventListener(
            'click',
            function (event) {

                if (
                    window.innerWidth <= 850 &&
                    sidebar.classList.contains('open') &&
                    !sidebar.contains(event.target) &&
                    !sidebarToggle.contains(event.target)
                ) {

                    sidebar.classList.remove('open');

                }

            }
        );

    </script>

@endpush