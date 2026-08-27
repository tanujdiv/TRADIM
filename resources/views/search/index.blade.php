@extends('layouts.app')

@section('title', 'Search - Tradim')

@section('content')

    <div class="tradim-search-page">

        <div class="search-heading">

            <h1>
                Search
            </h1>

            @if($query)

                <p>
                    Results for
                    <strong>"{{ $query }}"</strong>
                </p>

            @endif

        </div>


        {{-- =====================================================
        CHANNEL RESULTS
        ====================================================== --}}

        @if($channels->count())

            <section class="search-section">

                <div class="search-section-heading">

                    <h2>
                        Channels
                    </h2>

                    <span>
                        {{ $channels->count() }}
                        results
                    </span>

                </div>


                <div class="search-channel-list">

                    @foreach($channels as $channel)

                            <a href="{{ route(
                            'channels.show',
                            $channel->handle
                        ) }}" class="search-channel-card">

                                <div class="search-channel-avatar">

                                    @if($channel->avatar)

                                                <img src="{{ asset(
                                            'storage/' .
                                            $channel->avatar
                                        ) }}" alt="{{ $channel->name }}">

                                    @else

                                                {{ strtoupper(
                                            substr(
                                                $channel->name,
                                                0,
                                                1
                                            )
                                        ) }}

                                    @endif

                                </div>


                                <div class="search-channel-info">

                                    <h3>

                                        {{ $channel->name }}

                                        @if($channel->is_verified)

                                            <i class="bi bi-patch-check-fill"></i>

                                        @endif

                                    </h3>


                                    <p>
                                        @{{ $channel->handle }}
                                    </p>


                                    <span>

                                        {{ number_format(
                            $channel->subscriber_count
                        ) }}

                                        subscribers

                                        •

                                        {{ number_format(
                            $channel->video_count
                        ) }}

                                        videos

                                    </span>

                                </div>


                                <div class="search-channel-arrow">

                                    <i class="bi bi-chevron-right"></i>

                                </div>

                            </a>

                    @endforeach

                </div>

            </section>

        @endif


        {{-- =====================================================
        VIDEO RESULTS
        ====================================================== --}}

        @if($videos->count())

            <section class="search-section">

                <div class="search-section-heading">

                    <h2>
                        Videos
                    </h2>

                    <span>
                        Video results
                    </span>

                </div>


                <div class="search-video-grid">

                    @foreach($videos as $video)

                            <a href="{{ route(
                            'videos.show',
                            $video->slug
                        ) }}" class="search-video-card">

                                <div class="search-video-thumbnail">

                                    @if($video->thumbnail_path)

                                                <img src="{{ asset(
                                            'storage/' .
                                            $video->thumbnail_path
                                        ) }}" alt="{{ $video->title }}">

                                    @else

                                        <div class="search-video-placeholder">

                                            <i class="bi bi-play-fill"></i>

                                        </div>

                                    @endif

                                </div>


                                <div class="search-video-info">

                                    <h3>
                                        {{ $video->title }}
                                    </h3>


                                    <p>
                                        {{ $video->channel->name }}
                                    </p>


                                    <span>

                                        {{ number_format(
                            $video->views_count
                        ) }}

                                        views

                                        •

                                        {{ $video->published_at
                            ? $video->published_at->diffForHumans()
                            : 'Recently'
                                                }}

                                    </span>

                                </div>

                            </a>

                    @endforeach

                </div>


                <div class="search-pagination">

                    {{ $videos->appends([
                'q' => $query
            ])->links() }}

                </div>

            </section>

        @endif


        {{-- =====================================================
        NOTHING FOUND
        ====================================================== --}}

        @if(
                $query &&
                !$channels->count() &&
                !$videos->count()
            )

            <div class="search-empty">

                <div class="search-empty-icon">

                    <i class="bi bi-search"></i>

                </div>

                <h2>
                    No results found
                </h2>

                <p>
                    Try searching for another video or channel.
                </p>

            </div>

        @endif

    </div>


    <style>
        /* =========================================================
    SEARCH
    ========================================================= */

        .tradim-search-page {
            color: #f8fafc;
        }

        .search-heading {
            margin-bottom: 30px;
        }

        .search-heading h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 7px;
        }

        .search-heading p {
            color: #64748b;
            margin: 0;
        }

        .search-heading strong {
            color: #cbd5e1;
        }


        /* =========================================================
    SECTION
    ========================================================= */

        .search-section {
            margin-bottom: 45px;
        }

        .search-section-heading {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 18px;
        }

        .search-section-heading h2 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }

        .search-section-heading span {
            color: #64748b;
            font-size: 12px;
        }


        /* =========================================================
    CHANNEL
    ========================================================= */

        .search-channel-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .search-channel-card {
            display: flex;
            align-items: center;
            gap: 15px;

            padding: 15px;

            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 12px;

            text-decoration: none;

            transition: .2s;
        }

        .search-channel-card:hover {
            border-color: #7c3aed;
            transform: translateY(-1px);
        }

        .search-channel-avatar {
            width: 65px;
            height: 65px;

            flex: 0 0 65px;

            border-radius: 50%;

            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: center;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: #ffffff;

            font-size: 22px;
            font-weight: 800;
        }

        .search-channel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .search-channel-info {
            flex: 1;
        }

        .search-channel-info h3 {
            color: #ffffff;

            font-size: 15px;
            font-weight: 700;

            margin: 0 0 3px;
        }

        .search-channel-info h3 i {
            color: #60a5fa;
            font-size: 13px;
        }

        .search-channel-info p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0 0 5px;
        }

        .search-channel-info span {
            color: #64748b;
            font-size: 11px;
        }

        .search-channel-arrow {
            color: #64748b;
            font-size: 18px;
        }


        /* =========================================================
    VIDEOS
    ========================================================= */

        .search-video-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 22px;
        }

        .search-video-card {
            color: inherit;
            text-decoration: none;
        }

        .search-video-thumbnail {
            width: 100%;
            aspect-ratio: 16 / 9;

            overflow: hidden;

            border-radius: 10px;

            background: #151c2d;

            border: 1px solid #273149;
        }

        .search-video-thumbnail img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: .2s;
        }

        .search-video-card:hover .search-video-thumbnail img {
            transform: scale(1.04);
        }

        .search-video-placeholder {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #8b5cf6;

            font-size: 35px;
        }

        .search-video-info {
            padding-top: 10px;
        }

        .search-video-info h3 {
            color: #f1f5f9;

            font-size: 14px;
            font-weight: 700;

            line-height: 1.4;

            margin: 0 0 6px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
        }

        .search-video-info p {
            color: #94a3b8;

            font-size: 12px;

            margin: 0 0 3px;
        }

        .search-video-info span {
            color: #64748b;

            font-size: 11px;
        }


        /* =========================================================
    EMPTY
    ========================================================= */

        .search-empty {
            padding: 80px 20px;

            text-align: center;

            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 14px;
        }

        .search-empty-icon {
            color: #8b5cf6;

            font-size: 40px;
        }

        .search-empty h2 {
            color: #ffffff;

            font-size: 20px;

            margin: 15px 0 5px;
        }

        .search-empty p {
            color: #64748b;

            margin: 0;
        }


        /* =========================================================
    PAGINATION
    ========================================================= */

        .search-pagination {
            margin-top: 30px;
        }

        .search-pagination .page-link {
            background: #121a2b;
            border-color: #273149;
            color: #cbd5e1;
        }

        .search-pagination .active .page-link {
            background: #7c3aed;
            border-color: #7c3aed;
        }


        /* =========================================================
    RESPONSIVE
    ========================================================= */

        @media (max-width: 1100px) {

            .search-video-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

        }

        @media (max-width: 750px) {

            .search-video-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }

        @media (max-width: 500px) {

            .search-video-grid {
                grid-template-columns: 1fr;
            }

            .search-channel-avatar {
                width: 50px;
                height: 50px;
                flex-basis: 50px;
            }

        }
    </style>

@endsection