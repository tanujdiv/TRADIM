@extends('layouts.app')

@section('title', 'Search - ' . $query . ' - Tradim')

@section('content')

    <div class="tradim-search-page">


        {{-- =========================================================
        SEARCH HEADER
        ========================================================== --}}

        <div class="search-page-header">

            <h1>

                Search results for

                <span>
                    "{{ $query }}"
                </span>

            </h1>


            <p>

                Discover videos and creators on Tradim.

            </p>

        </div>



        {{-- =========================================================
        CHANNELS
        ========================================================== --}}

        @if($channels->count())

            <section class="search-section">

                <div class="search-section-title">

                    <h2>

                        Channels

                    </h2>

                </div>


                <div class="row g-3">

                    @foreach($channels as $channel)

                            <div class="col-xl-4
                                               col-lg-6
                                               col-md-6">

                                <div class="search-channel">

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

                                        </span>

                                    </div>

                                </div>

                            </div>

                    @endforeach

                </div>

            </section>

        @endif



        {{-- =========================================================
        VIDEOS
        ========================================================== --}}

        <section class="search-section">

            <div class="search-section-title">

                <h2>

                    Videos

                </h2>


                <span>

                    {{ $videos->total() }}
                    results

                </span>

            </div>


            @if($videos->count())

                <div class="row g-4">

                    @foreach($videos as $video)

                        <div class="col-xl-3
                                           col-lg-4
                                           col-md-6">

                            @include(
                                'videos.partials.card',
                                [
                                    'video' => $video
                                ]
                            )

                        </div>

                    @endforeach

                </div>


                <div class="tradim-pagination">

                    {{ $videos->links() }}

                </div>

            @else

                <div class="search-empty">

                    <div>

                        <i class="bi bi-search"></i>

                    </div>


                    <h3>

                        No videos found

                    </h3>


                    <p>

                        Try searching with a different
                        keyword.

                    </p>

                </div>

            @endif

        </section>

    </div>



    <style>
        /* =========================================================
       SEARCH PAGE
    ========================================================= */

        .tradim-search-page {

            color: #f8fafc;

        }


        .search-page-header {

            margin-bottom: 35px;

        }


        .search-page-header h1 {

            color: #ffffff;

            font-size: 25px;

            font-weight: 800;

            margin: 0 0 7px;

        }


        .search-page-header h1 span {

            color: #a78bfa;

        }


        .search-page-header p {

            color: #64748b;

            font-size: 13px;

            margin: 0;

        }


        /* =========================================================
       SEARCH SECTION
    ========================================================= */

        .search-section {

            margin-bottom: 45px;

        }


        .search-section-title {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 18px;

        }


        .search-section-title h2 {

            color: #ffffff;

            font-size: 20px;

            font-weight: 800;

            margin: 0;

        }


        .search-section-title span {

            color: #64748b;

            font-size: 12px;

        }


        /* =========================================================
       CHANNEL
    ========================================================= */

        .search-channel {

            display: flex;

            align-items: center;

            gap: 14px;

            padding: 15px;

            border-radius: 12px;

            background: #111a2b;

            border: 1px solid #273149;

        }


        .search-channel-avatar {

            width: 58px;

            height: 58px;

            flex: 0 0 58px;

            display: flex;

            align-items: center;

            justify-content: center;

            overflow: hidden;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: #ffffff;

            font-size: 18px;

            font-weight: 800;

        }


        .search-channel-avatar img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }


        .search-channel-info {

            min-width: 0;

        }


        .search-channel-info h3 {

            color: #ffffff;

            font-size: 15px;

            font-weight: 700;

            margin: 0 0 3px;

        }


        .search-channel-info h3 i {

            color: #60a5fa;

        }


        .search-channel-info p {

            color: #94a3b8;

            font-size: 11px;

            margin: 0 0 3px;

        }


        .search-channel-info span {

            color: #64748b;

            font-size: 10px;

        }


        /* =========================================================
       EMPTY
    ========================================================= */

        .search-empty {

            text-align: center;

            padding: 65px 20px;

            border-radius: 15px;

            background: #111a2b;

            border: 1px solid #273149;

        }


        .search-empty div {

            width: 65px;

            height: 65px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin: auto;

            border-radius: 50%;

            background: #1c2540;

            color: #8b5cf6;

            font-size: 27px;

        }


        .search-empty h3 {

            color: #ffffff;

            margin: 17px 0 7px;

        }


        .search-empty p {

            color: #64748b;

            margin: 0;

        }
    </style>

@endsection