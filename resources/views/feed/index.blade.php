@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Subscriptions
                </h2>

                <p class="text-muted mb-0">
                    Latest videos from channels you follow
                </p>
            </div>

            <a href="{{ route('feed.channels') }}" class="btn btn-outline-primary">
                My Channels
            </a>

        </div>


        {{-- Empty State --}}
        @if($videos->isEmpty())

            <div class="text-center py-5">

                <div style="font-size: 60px;">
                    📺
                </div>

                <h4 class="mt-3">
                    No videos yet
                </h4>

                <p class="text-muted">
                    Subscribe to channels to see their latest videos here.
                </p>

                <a href="{{ url('/') }}" class="btn btn-primary">
                    Explore Videos
                </a>

            </div>

        @else

            <div class="row g-4">

                @foreach($videos as $video)

                    @php
                        $isNew = $video->published_at
                            && $video->published_at->greaterThanOrEqualTo(
                                now()->subDays(2)
                            );
                    @endphp

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <div class="card h-100 border-0 shadow-sm">

                            {{-- Thumbnail --}}
                            <a href="{{ route('videos.show', $video->slug) }}" class="text-decoration-none">

                                <div class="position-relative">

                                    @if($video->thumbnail_path)

                                        <img src="{{ asset('storage/' . $video->thumbnail_path) }}" class="card-img-top"
                                            alt="{{ $video->title }}" style="
                                                            height: 190px;
                                                            object-fit: cover;
                                                        ">

                                    @else

                                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 190px;">
                                            <span class="text-white">
                                                No Thumbnail
                                            </span>
                                        </div>

                                    @endif


                                    @if($isNew)

                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                            NEW
                                        </span>

                                    @endif

                                </div>

                            </a>


                            {{-- Video Information --}}
                            <div class="card-body">

                                <h6 class="fw-bold mb-2">

                                    <a href="{{ route('videos.show', $video->slug) }}" class="text-decoration-none text-dark">
                                        {{ \Illuminate\Support\Str::limit($video->title, 70) }}
                                    </a>

                                </h6>


                                @if($video->channel)

                                    <div class="small text-muted mb-2">

                                        {{ $video->channel->name }}

                                        @if($video->channel->is_verified)

                                            <span class="text-primary" title="Verified">
                                                ✓
                                            </span>

                                        @endif

                                    </div>

                                @endif


                                <div class="small text-muted">

                                    {{ number_format($video->views_count) }}
                                    views

                                    <span class="mx-1">•</span>

                                    {{ $video->published_at?->diffForHumans() }}

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="mt-4">

                {{ $videos->links() }}

            </div>

        @endif

    </div>

@endsection