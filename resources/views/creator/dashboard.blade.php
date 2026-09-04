@extends('layouts.app')

@section('title', 'Creator Studio - Tradim')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="hero-badge">
                    <i class="bi bi-stars"></i>
                    CREATOR STUDIO
                </span>
                <h1 class="mt-3 mb-1">{{ $channel->name }}</h1>
                <p class="text-muted mb-0">{{ '@' . $channel->handle }}</p>
            </div>

            <a href="{{ route('videos.create') }}" class="btn-tradim">
                <i class="bi bi-cloud-arrow-up"></i>
                Upload Video
            </a>
        </div>

        {{-- STAT CARDS GRID --}}
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="tradim-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">TOTAL VIDEOS</small>
                            <h2 class="mt-2 mb-0">{{ number_format($totalVideos) }}</h2>
                        </div>
                        <i class="bi bi-play-btn fs-2 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="tradim-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">SUBSCRIBERS</small>
                            <h2 class="mt-2 mb-0">{{ number_format($totalSubscribers) }}</h2>
                        </div>
                        <i class="bi bi-people fs-2 text-success"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="tradim-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">TOTAL VIEWS</small>
                            <h2 class="mt-2 mb-0">{{ number_format($totalViews) }}</h2>
                        </div>
                        <i class="bi bi-eye fs-2 text-warning"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">

    <div class="tradim-card">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <small class="text-muted">
                    TOTAL WATCH TIME
                </small>

                @php
                    $hours = floor($totalWatchedSeconds / 3600);
                    $minutes = floor(($totalWatchedSeconds % 3600) / 60);
                    $seconds = $totalWatchedSeconds % 60;
                @endphp

                <h2 class="mt-2 mb-0">

                    @if($hours > 0)

                        {{ $hours }}h
                        {{ $minutes }}m

                    @elseif($minutes > 0)

                        {{ $minutes }}m
                        {{ $seconds }}s

                    @else

                        {{ $seconds }}s

                    @endif

                </h2>

                <small class="text-info">
                    {{ number_format($totalWatchedSeconds) }}
                    seconds watched
                </small>

            </div>

            <i class="bi bi-clock-history fs-2 text-info"></i>

        </div>

    </div>

</div>
        </div>

        {{-- VIDEOS TABLE --}}
        <div class="tradim-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Your Videos</h4>
                    <p class="text-muted mb-0">Manage your uploaded content.</p>
                </div>

                <a href="{{ route('videos.create') }}" class="btn-tradim">
                    <i class="bi bi-plus-lg"></i>
                    New Video
                </a>
            </div>

            @if($videos->count())
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Video</th>
                                <th>Category</th>
                                <th>Views</th>
                                <th>Status</th>
                                <th>Visibility</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($video->thumbnail_path)
                                                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" style="width:120px; height:68px; object-fit:cover; border-radius:8px;">
                                            @else
                                                <div style="width:120px; height:68px; border-radius:8px; background:#151c30; display:flex; align-items:center; justify-content:center;">
                                                    <i class="bi bi-play-circle fs-3"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $video->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($video->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $video->category?->name ?? 'Uncategorized' }}</td>
                                    <td>{{ number_format($video->views_count ?? 0) }}</td>
                                    <td>
                                        @if($video->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($video->status === 'processing')
                                            <span class="badge bg-warning">Processing</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($video->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($video->visibility) }}</td>
                                    <td>{{ $video->created_at ? $video->created_at->format('d M Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $videos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-camera-video-off fs-1 text-muted"></i>
                    <h4 class="mt-3">No videos yet</h4>
                    <p class="text-muted">Upload your first video and start building your audience.</p>
                    <a href="{{ route('videos.create') }}" class="btn-tradim">Upload Your First Video</a>
                </div>
            @endif
        </div>

     {{-- VIDEO WATCH TIME ANALYTICS SECTION --}}

<div class="tradim-card creator-video-analytics">

    <div class="mb-4">
        <h4 class="mb-1">
            Video Watch Time Analytics
        </h4>

        <p class="text-muted mb-0">
            See how much time viewers have watched your videos.
        </p>
    </div>

    @forelse($videos as $video)

        @php

            $watchSeconds = (int) (
                $video->total_watched_seconds ?? 0
            );

            $videoViews = (int) (
                $video->views_count ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | Total Watch Time
            |--------------------------------------------------------------------------
            */

            $hours = floor(
                $watchSeconds / 3600
            );

            $minutes = floor(
                ($watchSeconds % 3600) / 60
            );

            $seconds = $watchSeconds % 60;

            /*
            |--------------------------------------------------------------------------
            | Average Watch Time
            |--------------------------------------------------------------------------
            */

            $averageSeconds = $videoViews > 0
                ? floor($watchSeconds / $videoViews)
                : 0;

            $averageMinutes = floor(
                $averageSeconds / 60
            );

            $averageRemainingSeconds =
                $averageSeconds % 60;

        @endphp

        <div
            class="analytics-video-row d-flex flex-wrap justify-content-between align-items-center py-3 border-bottom border-secondary gap-3">

            {{-- VIDEO --}}

            <div
                class="analytics-video-title"
                style="flex:1; min-width:220px;">

                <strong class="d-block">
                    {{ $video->title }}
                </strong>

                <small class="text-muted">
                    {{ $video->created_at
                        ? $video->created_at->format('d M Y')
                        : ''
                    }}
                </small>

            </div>


            {{-- VIEWS --}}

            <div
                class="text-center"
                style="min-width:100px;">

                <span class="text-muted d-block small">
                    Views
                </span>

                <strong>
                    {{ number_format($videoViews) }}
                </strong>

            </div>


            {{-- TOTAL WATCH TIME --}}

            <div
                class="text-center"
                style="min-width:150px;">

                <span class="text-muted d-block small">
                    Total Watch Time
                </span>

                <strong class="text-success">

                    @if($hours > 0)

                        {{ $hours }}h
                        {{ $minutes }}m
                        {{ $seconds }}s

                    @elseif($minutes > 0)

                        {{ $minutes }}m
                        {{ $seconds }}s

                    @else

                        {{ $seconds }}s

                    @endif

                </strong>

            </div>


            {{-- AVERAGE WATCH TIME --}}

            <div
                class="text-center"
                style="min-width:150px;">

                <span class="text-muted d-block small">
                    Avg. Watch Time
                </span>

                <strong class="text-info">

                    @if($averageMinutes > 0)

                        {{ $averageMinutes }}m
                        {{ $averageRemainingSeconds }}s

                    @else

                        {{ $averageSeconds }}s

                    @endif

                </strong>

            </div>

        </div>

    @empty

        <p class="text-muted mb-0">
            No video analytics available.
        </p>

    @endforelse

</div>

    </div>

@endsection