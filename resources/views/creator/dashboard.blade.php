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

                <h1 class="mt-3 mb-1">
                    {{ $channel->name }}
                </h1>

                <p class="text-muted mb-0">
                    @{{ $channel->handle }}
                </p>

            </div>


            <a href="{{ route('videos.create') }}" class="btn-tradim">

                <i class="bi bi-cloud-arrow-up"></i>

                Upload Video

            </a>

        </div>


        {{-- SUCCESS MESSAGE --}}

        @if(session('success'))

            <div class="alert alert-success">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- ERROR MESSAGE --}}

        @if(session('error'))

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

            </div>

        @endif


        {{-- CHANNEL HEADER --}}

        <div class="tradim-card mb-4">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        <div class="creator-avatar" style="
                                width:80px;
                                height:80px;
                                border-radius:50%;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-size:30px;
                                font-weight:800;
                                background:linear-gradient(
                                    135deg,
                                    #7c3aed,
                                    #ec4899
                                );
                                color:white;
                            ">

                            {{ strtoupper(
        substr($channel->name, 0, 1)
    ) }}

                        </div>


                        <div>

                            <h3 class="mb-1">

                                {{ $channel->name }}

                                @if($channel->is_verified)

                                    <i class="bi bi-patch-check-fill text-primary"></i>

                                @endif

                            </h3>


                            <p class="text-muted mb-0">

                                {{ $channel->description
        ?: 'Welcome to my Tradim channel.'
                                }}

                            </p>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="row text-center">

                        <div class="col-4">

                            <h4 class="mb-0">
                                {{ number_format(
        $channel->video_count
    ) }}
                            </h4>

                            <small class="text-muted">
                                Videos
                            </small>

                        </div>


                        <div class="col-4">

                            <h4 class="mb-0">
                                {{ number_format(
        $channel->subscriber_count
    ) }}
                            </h4>

                            <small class="text-muted">
                                Subscribers
                            </small>

                        </div>


                        <div class="col-4">

                            <h4 class="mb-0">
                                {{ number_format(
        $channel->total_views
    ) }}
                            </h4>

                            <small class="text-muted">
                                Views
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- STAT CARDS --}}

        <div class="row g-4 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="tradim-card">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                TOTAL VIDEOS
                            </small>

                            <h2 class="mt-2 mb-0">
                                {{ number_format(
        $channel->video_count
    ) }}
                            </h2>

                        </div>

                        <i class="bi bi-play-btn fs-2 text-primary"></i>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="tradim-card">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                SUBSCRIBERS
                            </small>

                            <h2 class="mt-2 mb-0">
                                {{ number_format(
        $channel->subscriber_count
    ) }}
                            </h2>

                        </div>

                        <i class="bi bi-people fs-2 text-success"></i>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="tradim-card">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                TOTAL VIEWS
                            </small>

                            <h2 class="mt-2 mb-0">
                                {{ number_format(
        $channel->total_views
    ) }}
                            </h2>

                        </div>

                        <i class="bi bi-eye fs-2 text-warning"></i>

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="tradim-card">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                STATUS
                            </small>

                            <h2 class="mt-2 mb-0">

                                <span class="text-success">
                                    Active
                                </span>

                            </h2>

                        </div>

                        <i class="bi bi-check-circle fs-2 text-success"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- VIDEOS --}}

        <div class="tradim-card">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h4 class="mb-1">
                        Your Videos
                    </h4>

                    <p class="text-muted mb-0">
                        Manage your uploaded content.
                    </p>

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

                                <th>
                                    Video
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Views
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Visibility
                                </th>

                                <th>
                                    Date
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($videos as $video)

                                            <tr>

                                                <td>

                                                    <div class="d-flex align-items-center gap-3">

                                                        @if($video->thumbnail_path)

                                                                                <img src="{{ asset(
                                                                'storage/' .
                                                                $video->thumbnail_path
                                                            ) }}" style="
                                                                                                    width:120px;
                                                                                                    height:68px;
                                                                                                    object-fit:cover;
                                                                                                    border-radius:8px;
                                                                                                ">

                                                        @else

                                                            <div style="
                                                                                width:120px;
                                                                                height:68px;
                                                                                border-radius:8px;
                                                                                background:#151c30;
                                                                                display:flex;
                                                                                align-items:center;
                                                                                justify-content:center;
                                                                            ">

                                                                <i class="bi bi-play-circle fs-3"></i>

                                                            </div>

                                                        @endif


                                                        <div>

                                                            <strong>
                                                                {{ $video->title }}
                                                            </strong>

                                                            <br>

                                                            <small class="text-muted">
                                                                {{ Str::limit(
                                    $video->description,
                                    60
                                ) }}
                                                            </small>

                                                        </div>

                                                    </div>

                                                </td>


                                                <td>

                                                    {{ $video->category?->name ?? 'Uncategorized' }}

                                                </td>


                                                <td>

                                                    {{ number_format(
                                    $video->views_count
                                ) }}

                                                </td>


                                                <td>

                                                    @if($video->status === 'published')

                                                        <span class="badge bg-success">
                                                            Published
                                                        </span>

                                                    @elseif($video->status === 'processing')

                                                        <span class="badge bg-warning">
                                                            Processing
                                                        </span>

                                                    @else

                                                                        <span class="badge bg-secondary">
                                                                            {{ ucfirst(
                                                            $video->status
                                                        ) }}
                                                                        </span>

                                                    @endif

                                                </td>


                                                <td>

                                                    {{ ucfirst(
                                    $video->visibility
                                ) }}

                                                </td>


                                                <td>

                                                    {{ $video->created_at->format(
                                    'd M Y'
                                ) }}

                                                </td>

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

                    <h4 class="mt-3">
                        No videos yet
                    </h4>

                    <p class="text-muted">
                        Upload your first video and start building your audience.
                    </p>


                    <a href="{{ route('videos.create') }}" class="btn-tradim">

                        Upload Your First Video

                    </a>

                </div>


            @endif

        </div>

    </div>

@endsection