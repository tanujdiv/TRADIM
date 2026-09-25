@extends('layouts.app')


@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $videos */
    /** @var \Illuminate\Support\Collection $channels */
@endphp

@section('content')

    <style>

        .tradim-search-page {
            min-height: calc(100vh - 70px);
            padding: 28px 24px 60px;
            color: #ffffff;
        }

        .tradim-search-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        .tradim-search-header {
            margin-bottom: 24px;
        }

        .tradim-search-heading {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .tradim-search-subtitle {
            color: #94a3b8;
            font-size: 14px;
        }

        .tradim-search-query {
            color: #a78bfa;
        }

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        .tradim-search-filters {
            background: #10172a;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 28px;
        }

        .tradim-search-filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
        }

        .tradim-filter-group label {
            display: block;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 7px;
        }

        .tradim-filter-control {
            width: 100%;
            height: 42px;

            background: #0b1020;
            color: #ffffff;

            border: 1px solid rgba(255,255,255,.10);
            border-radius: 9px;

            padding: 0 12px;

            font-size: 13px;
            outline: none;
        }

        .tradim-filter-control:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,.12);
        }

        .tradim-filter-button {
            height: 42px;
            padding: 0 18px;

            border: 0;
            border-radius: 9px;

            background: #7c3aed;
            color: #ffffff;

            font-size: 13px;
            font-weight: 700;

            cursor: pointer;
        }

        .tradim-filter-button:hover {
            background: #6d28d9;
        }

        /*
        |--------------------------------------------------------------------------
        | Search Type Tabs
        |--------------------------------------------------------------------------
        */

        .tradim-search-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .tradim-search-tab {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 8px 15px;

            border-radius: 20px;

            background: #10172a;
            border: 1px solid rgba(255,255,255,.08);

            color: #94a3b8;

            text-decoration: none;

            font-size: 13px;
            font-weight: 600;
        }

        .tradim-search-tab:hover {
            color: #ffffff;
            border-color: rgba(124,58,237,.5);
        }

        .tradim-search-tab.active {
            background: #7c3aed;
            color: #ffffff;
            border-color: #7c3aed;
        }

        /*
        |--------------------------------------------------------------------------
        | Section
        |--------------------------------------------------------------------------
        */

        .tradim-search-section {
            margin-bottom: 38px;
        }

        .tradim-search-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 16px;
        }

        .tradim-search-section-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .tradim-search-result-count {
            color: #64748b;
            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Video Grid
        |--------------------------------------------------------------------------
        */

        .tradim-search-video-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .tradim-search-video-card {
            min-width: 0;
        }

        .tradim-search-thumbnail {
            position: relative;

            width: 100%;
            aspect-ratio: 16 / 9;

            background: #0b1020;
            border-radius: 12px;

            overflow: hidden;

            margin-bottom: 10px;
        }

        .tradim-search-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;

            transition: transform .25s ease;
        }

        .tradim-search-video-card:hover
        .tradim-search-thumbnail img {
            transform: scale(1.035);
        }

        .tradim-search-no-thumbnail {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: linear-gradient(
                135deg,
                #111827,
                #1e1b4b
            );

            color: #64748b;
            font-size: 30px;
        }

        .tradim-search-duration {
            position: absolute;

            right: 8px;
            bottom: 8px;

            padding: 3px 6px;

            border-radius: 5px;

            background: rgba(0,0,0,.82);
            color: #ffffff;

            font-size: 10px;
            font-weight: 700;
        }

        .tradim-search-video-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;

            color: #ffffff;

            font-size: 14px;
            line-height: 1.45;
            font-weight: 700;

            text-decoration: none;

            margin-bottom: 5px;
        }

        .tradim-search-video-title:hover {
            color: #c4b5fd;
        }

        .tradim-search-channel-name {
            display: block;

            color: #94a3b8;

            font-size: 12px;
            text-decoration: none;

            margin-bottom: 3px;
        }

        .tradim-search-channel-name:hover {
            color: #ffffff;
        }

        .tradim-search-video-meta {
            color: #64748b;
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | Channels
        |--------------------------------------------------------------------------
        */

        .tradim-search-channel-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .tradim-search-channel-card {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 14px;

            background: #10172a;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;

            text-decoration: none;

            transition: .2s ease;
        }

        .tradim-search-channel-card:hover {
            background: #161f36;
            border-color: rgba(124,58,237,.35);
            transform: translateY(-2px);
        }

        .tradim-search-channel-avatar {
            width: 52px;
            height: 52px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(
                135deg,
                #7c3aed,
                #ec4899
            );

            color: #ffffff;

            font-size: 19px;
            font-weight: 800;

            overflow: hidden;
        }

        .tradim-search-channel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tradim-search-channel-info {
            min-width: 0;
        }

        .tradim-search-channel-title {
            color: #ffffff;

            font-size: 14px;
            font-weight: 700;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

            margin-bottom: 3px;
        }

        .tradim-search-channel-handle {
            color: #94a3b8;

            font-size: 11px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .tradim-search-empty {
            padding: 70px 20px;

            text-align: center;

            background: #10172a;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
        }

        .tradim-search-empty-icon {
            font-size: 42px;
            color: #475569;
            margin-bottom: 15px;
        }

        .tradim-search-empty h3 {
            color: #ffffff;
            font-size: 18px;
            margin-bottom: 7px;
        }

        .tradim-search-empty p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        .tradim-search-pagination {
            margin-top: 24px;
        }

        .tradim-search-pagination .pagination {
            margin-bottom: 0;
        }

        .tradim-search-pagination .page-link {
            background: #10172a;
            border-color: rgba(255,255,255,.08);
            color: #cbd5e1;
        }

        .tradim-search-pagination .page-item.active .page-link {
            background: #7c3aed;
            border-color: #7c3aed;
            color: #ffffff;
        }

        .tradim-search-pagination .page-link:hover {
            background: #161f36;
            color: #ffffff;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1200px) {

            .tradim-search-filter-row {
                grid-template-columns: repeat(3, 1fr);
            }

            .tradim-search-video-grid,
            .tradim-search-channel-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {

            .tradim-search-page {
                padding: 20px 14px 40px;
            }

            .tradim-search-filter-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .tradim-search-video-grid,
            .tradim-search-channel-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 576px) {

            .tradim-search-filter-row {
                grid-template-columns: 1fr;
            }

            .tradim-search-video-grid,
            .tradim-search-channel-grid {
                grid-template-columns: 1fr;
            }

            .tradim-search-heading {
                font-size: 20px;
            }
        }

    </style>


    <div class="tradim-search-page">

        <div class="tradim-search-container">

            {{-- HEADER --}}
            <div class="tradim-search-header">

                @if($hasSearch)

                    <div class="tradim-search-heading">

                        Search results for

                        <span class="tradim-search-query">
                            "{{ $query }}"
                        </span>

                    </div>

                    <div class="tradim-search-subtitle">
                        Find videos and creators on Tradim.
                    </div>

                @else

                    <div class="tradim-search-heading">
                        Search Tradim
                    </div>

                    <div class="tradim-search-subtitle">
                        Search for videos, creators and channels.
                    </div>

                @endif

            </div>


            @if($hasSearch)

                {{-- FILTERS --}}
                <div class="tradim-search-filters">

                    <form method="GET" action="{{ route('search') }}">

                        <input
                            type="hidden"
                            name="q"
                            value="{{ $query }}"
                        >

                        <div class="tradim-search-filter-row">

                            {{-- CATEGORY --}}
                            <div class="tradim-filter-group">

                                <label>
                                    Category
                                </label>

                                <select
                                    name="category"
                                    class="tradim-filter-control"
                                >

                                    <option value="">
                                        All categories
                                    </option>

                                    @foreach($categories as $item)

                                        <option
                                            value="{{ $item->slug }}"
                                            @selected($category === $item->slug)
                                        >
                                            {{ $item->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- DURATION --}}
                            <div class="tradim-filter-group">

                                <label>
                                    Duration
                                </label>

                                <select
                                    name="duration"
                                    class="tradim-filter-control"
                                >

                                    <option
                                        value="all"
                                        @selected($duration === 'all')
                                    >
                                        Any length
                                    </option>

                                    <option
                                        value="short"
                                        @selected($duration === 'short')
                                    >
                                        Under 4 min
                                    </option>

                                    <option
                                        value="medium"
                                        @selected($duration === 'medium')
                                    >
                                        4–20 min
                                    </option>

                                    <option
                                        value="long"
                                        @selected($duration === 'long')
                                    >
                                        Over 20 min
                                    </option>

                                </select>

                            </div>


                            {{-- DATE --}}
                            <div class="tradim-filter-group">

                                <label>
                                    Upload date
                                </label>

                                <select
                                    name="date"
                                    class="tradim-filter-control"
                                >

                                    <option
                                        value="all"
                                        @selected($date === 'all')
                                    >
                                        Any time
                                    </option>

                                    <option
                                        value="today"
                                        @selected($date === 'today')
                                    >
                                        Today
                                    </option>

                                    <option
                                        value="week"
                                        @selected($date === 'week')
                                    >
                                        This week
                                    </option>

                                    <option
                                        value="month"
                                        @selected($date === 'month')
                                    >
                                        This month
                                    </option>

                                    <option
                                        value="year"
                                        @selected($date === 'year')
                                    >
                                        This year
                                    </option>

                                </select>

                            </div>


                            {{-- SORT --}}
                            <div class="tradim-filter-group">

                                <label>
                                    Sort
                                </label>

                                <select
                                    name="sort"
                                    class="tradim-filter-control"
                                >

                                    <option
                                        value="relevance"
                                        @selected($sort === 'relevance')
                                    >
                                        Relevance
                                    </option>

                                    <option
                                        value="latest"
                                        @selected($sort === 'latest')
                                    >
                                        Latest
                                    </option>

                                    <option
                                        value="views"
                                        @selected($sort === 'views')
                                    >
                                        Most viewed
                                    </option>

                                    <option
                                        value="likes"
                                        @selected($sort === 'likes')
                                    >
                                        Most liked
                                    </option>

                                </select>

                            </div>


                            {{-- RESULTS PER PAGE --}}
                            <div class="tradim-filter-group">

                                <label>
                                    Results
                                </label>

                                <select
                                    name="per_page"
                                    class="tradim-filter-control"
                                >

                                    <option
                                        value="12"
                                        @selected($perPage === 12)
                                    >
                                        12
                                    </option>

                                    <option
                                        value="24"
                                        @selected($perPage === 24)
                                    >
                                        24
                                    </option>

                                    <option
                                        value="48"
                                        @selected($perPage === 48)
                                    >
                                        48
                                    </option>

                                </select>

                            </div>


                            <div>

                                <button
                                    type="submit"
                                    class="tradim-filter-button"
                                >
                                    <i class="bi bi-funnel me-1"></i>
                                    Apply
                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                {{-- TYPE TABS --}}
                <div class="tradim-search-tabs">

                    <a
                        href="{{ route('search', [
                            'q' => $query,
                            'category' => $category,
                            'duration' => $duration,
                            'date' => $date,
                            'sort' => $sort,
                            'per_page' => $perPage,
                            'type' => 'all',
                        ]) }}"
                        class="tradim-search-tab {{ $type === 'all' ? 'active' : '' }}"
                    >
                        <i class="bi bi-grid"></i>
                        All
                    </a>


                    <a
                        href="{{ route('search', [
                            'q' => $query,
                            'category' => $category,
                            'duration' => $duration,
                            'date' => $date,
                            'sort' => $sort,
                            'per_page' => $perPage,
                            'type' => 'videos',
                        ]) }}"
                        class="tradim-search-tab {{ $type === 'videos' ? 'active' : '' }}"
                    >
                        <i class="bi bi-play-btn"></i>
                        Videos
                    </a>


                    <a
                        href="{{ route('search', [
                            'q' => $query,
                            'type' => 'channels',
                        ]) }}"
                        class="tradim-search-tab {{ $type === 'channels' ? 'active' : '' }}"
                    >
                        <i class="bi bi-person-video3"></i>
                        Channels
                    </a>

                </div>


                {{-- CHANNELS --}}
                @if($type !== 'videos')

                    @if($channels->count())

                        <section class="tradim-search-section">

                            <div class="tradim-search-section-header">

                                <h2 class="tradim-search-section-title">
                                    Channels
                                </h2>

                                <span class="tradim-search-result-count">
                                    {{ $channels->count() }} found
                                </span>

                            </div>


                            <div class="tradim-search-channel-grid">

                                @foreach($channels as $channel)

                                    <a
                                        href="{{ route('channel.show', $channel->handle) }}"
                                        class="tradim-search-channel-card"
                                    >

                                        <div class="tradim-search-channel-avatar">

                                            @if($channel->avatar)

                                                <img
                                                    src="{{ asset('storage/' . $channel->avatar) }}"
                                                    alt="{{ $channel->name }}"
                                                >

                                            @else

                                                {{ strtoupper(
                                                    substr($channel->name ?: 'C', 0, 1)
                                                ) }}

                                            @endif

                                        </div>


                                        <div class="tradim-search-channel-info">

                                            <div class="tradim-search-channel-title">
                                                {{ $channel->name }}
                                            </div>

                                            <div class="tradim-search-channel-handle">

                                                @if($channel->handle)
                                                    {{ '@' . $channel->handle }}
                                                @else
                                                    Creator channel
                                                @endif

                                            </div>

                                        </div>

                                    </a>

                                @endforeach

                            </div>

                        </section>

                    @endif

                @endif


                {{-- VIDEOS --}}
                @if($type !== 'channels')

                    <section class="tradim-search-section">

                        <div class="tradim-search-section-header">

                            <h2 class="tradim-search-section-title">
                                Videos
                            </h2>

                            @if($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                                <span class="tradim-search-result-count">
                                    {{ $videos->total() }} results
                                </span>

                            @endif

                        </div>


                        @if($videos->count())

                            <div class="tradim-search-video-grid">

                                @foreach($videos as $video)

                                    <article class="tradim-search-video-card">

                                        <a
                                            href="{{ route('watch', $video->slug) }}"
                                            class="text-decoration-none"
                                        >

                                            <div class="tradim-search-thumbnail">

                                                @if($video->thumbnail_path)

                                                    <img
                                                        src="{{ asset('storage/' . $video->thumbnail_path) }}"
                                                        alt="{{ $video->title }}"
                                                        loading="lazy"
                                                    >

                                                @else

                                                    <div class="tradim-search-no-thumbnail">

                                                        <i class="bi bi-play-circle"></i>

                                                    </div>

                                                @endif


                                                @if($video->duration !== null)

                                                    <span class="tradim-search-duration">

                                                        {{ gmdate(
                                                            'H:i:s',
                                                            (int) $video->duration
                                                        ) }}

                                                    </span>

                                                @endif

                                            </div>

                                        </a>


                                        <a
                                            href="{{ route('watch', $video->slug) }}"
                                            class="tradim-search-video-title"
                                        >
                                            {{ $video->title }}
                                        </a>


                                        @if($video->channel)

                                            <a
                                                href="{{ route('channel.show', $video->channel->handle) }}"
                                                class="tradim-search-channel-name"
                                            >
                                                {{ $video->channel->name }}
                                            </a>

                                        @endif


                                        <div class="tradim-search-video-meta">

                                            {{ number_format(
                                                (int) $video->views_count
                                            ) }}
                                            views

                                            @if($video->published_at)
                                                •
                                                {{ $video->published_at->diffForHumans() }}
                                            @endif

                                        </div>

                                    </article>

                                @endforeach

                            </div>


                            @if($videos instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                                <div class="tradim-search-pagination">

                                    {{ $videos->links() }}

                                </div>

                            @endif

                        @else

                            <div class="tradim-search-empty">

                                <div class="tradim-search-empty-icon">
                                    <i class="bi bi-search"></i>
                                </div>

                                <h3>
                                    No videos found
                                </h3>

                                <p>
                                    Try a different keyword or change your filters.
                                </p>

                            </div>

                        @endif

                    </section>

                @endif

            @else

                {{-- EMPTY SEARCH --}}
                <div class="tradim-search-empty">

                    <div class="tradim-search-empty-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h3>
                        Search for something
                    </h3>

                    <p>
                        Use the search box above to find videos and creators.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection