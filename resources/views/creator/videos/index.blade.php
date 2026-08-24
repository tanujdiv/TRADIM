@extends('layouts.app')

@section('title', 'My Videos - Tradim')

@section('content')

    <div class="creator-videos-page">

        {{-- =========================================================
        HEADER
        ========================================================== --}}

        <div class="page-header">

            <div>
                <h1>
                    <i class="bi bi-collection-play"></i>
                    My Videos
                </h1>

                <p>
                    Manage all videos uploaded to
                    {{ $channel->name }}
                </p>
            </div>

            <a href="{{ route('videos.create') }}" class="upload-btn">
                <i class="bi bi-cloud-arrow-up"></i>
                Upload Video
            </a>

        </div>


        {{-- =========================================================
        SUCCESS
        ========================================================== --}}

        @if(session('success'))

            <div class="tradim-alert success-alert">

                <i class="bi bi-check-circle-fill"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- =========================================================
        ERROR
        ========================================================== --}}

        @if(session('error'))

            <div class="tradim-alert error-alert">

                <i class="bi bi-exclamation-circle-fill"></i>

                {{ session('error') }}

            </div>

        @endif


        {{-- =========================================================
        VIDEO COUNT
        ========================================================== --}}

        <div class="video-summary">

            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-play-btn-fill"></i>
                </div>

                <div>

                    <span>
                        Total Videos
                    </span>

                    <strong>
                        {{ $videos->total() }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =========================================================
        VIDEOS
        ========================================================== --}}

        @if($videos->count())

            <div class="videos-grid">

                @foreach($videos as $video)

                    <div class="video-card">

                        {{-- THUMBNAIL --}}

                        <a href="{{ route('videos.show', $video->slug) }}" class="video-thumbnail">

                            @if($video->thumbnail_path)

                                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}">

                            @else

                                <div class="no-thumbnail">

                                    <i class="bi bi-play-circle-fill"></i>

                                </div>

                            @endif

                            <span class="status-badge">

                                {{ ucfirst($video->visibility) }}

                            </span>

                        </a>


                        {{-- CONTENT --}}

                        <div class="video-content">

                            <h3>

                                {{ $video->title }}

                            </h3>


                            <div class="video-meta">

                                <span>
                                    <i class="bi bi-eye"></i>
                                    {{ number_format($video->views_count) }}
                                </span>

                                <span>
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    {{ number_format($video->likes_count) }}
                                </span>

                                <span>
                                    <i class="bi bi-chat"></i>
                                    {{ number_format($video->comments_count) }}
                                </span>

                            </div>


                            @if($video->category)

                                <div class="category-name">

                                    <i class="bi bi-tag"></i>

                                    {{ $video->category->name }}

                                </div>

                            @endif


                            <div class="video-actions">

                                <a href="{{ route('videos.show', $video->slug) }}" class="action-view">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>


                                <a href="{{ route('creator.videos.edit', $video->id) }}" class="action-edit">
                                    <i class="bi bi-pencil"></i>
                                    Edit
                                </a>


                                <form method="POST" action="{{ route('creator.videos.destroy', $video->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this video?');">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="action-delete">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- PAGINATION --}}

            <div class="pagination-wrapper">

                {{ $videos->links() }}

            </div>

        @else

            <div class="empty-videos">

                <div class="empty-icon">

                    <i class="bi bi-camera-video-off"></i>

                </div>

                <h2>
                    No videos yet
                </h2>

                <p>
                    Upload your first video and start building your Tradim channel.
                </p>

                <a href="{{ route('videos.create') }}" class="upload-btn">
                    <i class="bi bi-cloud-arrow-up"></i>
                    Upload Your First Video
                </a>

            </div>

        @endif

    </div>


    <style>
        /* =========================================================
       PAGE
    ========================================================= */

        .creator-videos-page {
            color: #f8fafc;
        }


        /* =========================================================
       HEADER
    ========================================================= */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 7px;

            color: #ffffff;

            font-size: 28px;
            font-weight: 800;
        }

        .page-header h1 i {
            color: #8b5cf6;
        }

        .page-header p {
            margin: 0;

            color: #94a3b8;

            font-size: 14px;
        }


        /* =========================================================
       UPLOAD BUTTON
    ========================================================= */

        .upload-btn {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 11px 18px;

            border-radius: 10px;

            background: #7c3aed;

            color: #ffffff !important;

            font-size: 14px;
            font-weight: 700;

            text-decoration: none;

            border: 0;

            transition: .2s;
        }

        .upload-btn:hover {
            background: #6d28d9;

            color: #ffffff !important;

            transform: translateY(-1px);
        }


        /* =========================================================
       ALERT
    ========================================================= */

        .tradim-alert {
            display: flex;

            align-items: center;

            gap: 10px;

            padding: 13px 16px;

            margin-bottom: 20px;

            border-radius: 10px;

            font-size: 14px;
            font-weight: 600;
        }

        .success-alert {
            background: rgba(34, 197, 94, .12);

            border: 1px solid rgba(34, 197, 94, .25);

            color: #86efac;
        }

        .error-alert {
            background: rgba(239, 68, 68, .12);

            border: 1px solid rgba(239, 68, 68, .25);

            color: #fca5a5;
        }


        /* =========================================================
       SUMMARY
    ========================================================= */

        .video-summary {
            margin-bottom: 25px;
        }

        .summary-card {
            width: fit-content;

            min-width: 190px;

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 15px 18px;

            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 12px;
        }

        .summary-icon {
            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: rgba(124, 58, 237, .15);

            color: #a78bfa;

            font-size: 20px;
        }

        .summary-card span {
            display: block;

            color: #64748b;

            font-size: 12px;

            margin-bottom: 3px;
        }

        .summary-card strong {
            color: #ffffff;

            font-size: 20px;
        }


        /* =========================================================
       GRID
    ========================================================= */

        .videos-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 20px;
        }


        /* =========================================================
       VIDEO CARD
    ========================================================= */

        .video-card {
            overflow: hidden;

            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 14px;

            transition: .2s;
        }

        .video-card:hover {
            border-color: #3f4d6a;

            transform: translateY(-2px);
        }


        /* =========================================================
       THUMBNAIL
    ========================================================= */

        .video-thumbnail {
            position: relative;

            display: block;

            width: 100%;

            aspect-ratio: 16 / 9;

            overflow: hidden;

            background: #070b13;

            text-decoration: none;
        }

        .video-thumbnail img {
            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: .25s;
        }

        .video-card:hover .video-thumbnail img {
            transform: scale(1.04);
        }

        .no-thumbnail {
            width: 100%;
            height: 100%;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(135deg,
                    #181d32,
                    #0d1220);

            color: #8b5cf6;

            font-size: 45px;
        }


        /* =========================================================
       STATUS
    ========================================================= */

        .status-badge {
            position: absolute;

            right: 10px;
            bottom: 10px;

            padding: 5px 9px;

            border-radius: 6px;

            background: rgba(0, 0, 0, .75);

            color: #ffffff;

            font-size: 11px;

            font-weight: 700;

            text-transform: capitalize;
        }


        /* =========================================================
       CONTENT
    ========================================================= */

        .video-content {
            padding: 15px;
        }

        .video-content h3 {
            margin: 0 0 10px;

            color: #ffffff;

            font-size: 15px;

            line-height: 1.45;

            font-weight: 700;

            display: -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;
        }


        /* =========================================================
       META
    ========================================================= */

        .video-meta {
            display: flex;

            flex-wrap: wrap;

            gap: 13px;

            margin-bottom: 10px;
        }

        .video-meta span {
            color: #8491a7;

            font-size: 11px;
        }

        .video-meta i {
            margin-right: 3px;

            color: #8b5cf6;
        }


        /* =========================================================
       CATEGORY
    ========================================================= */

        .category-name {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            margin-bottom: 13px;

            color: #94a3b8;

            font-size: 11px;
        }

        .category-name i {
            color: #8b5cf6;
        }


        /* =========================================================
       ACTIONS
    ========================================================= */

        .video-actions {
            display: flex;

            align-items: center;

            gap: 7px;

            padding-top: 12px;

            border-top: 1px solid #222c40;
        }

        .video-actions form {
            margin: 0;
        }

        .video-actions a,
        .video-actions button {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 7px 9px;

            border-radius: 7px;

            border: 1px solid #2a354c;

            background: #171f31;

            color: #cbd5e1;

            font-size: 11px;

            font-weight: 600;

            text-decoration: none;

            cursor: pointer;
        }

        .video-actions a:hover {
            color: #ffffff;

            border-color: #7c3aed;
        }

        .action-delete:hover {
            color: #fca5a5 !important;

            border-color: #ef4444 !important;
        }


        /* =========================================================
       EMPTY
    ========================================================= */

        .empty-videos {
            padding: 70px 20px;

            text-align: center;

            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 14px;
        }

        .empty-icon {
            width: 70px;
            height: 70px;

            margin: 0 auto 18px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background: rgba(124, 58, 237, .12);

            color: #8b5cf6;

            font-size: 32px;
        }

        .empty-videos h2 {
            margin: 0 0 8px;

            color: #ffffff;

            font-size: 21px;
        }

        .empty-videos p {
            margin: 0 auto 22px;

            color: #64748b;

            font-size: 13px;
        }


        /* =========================================================
       PAGINATION
    ========================================================= */

        .pagination-wrapper {
            margin-top: 30px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper .pagination {
            margin: 0;
        }

        .pagination-wrapper .page-link {
            background: #121a2b;

            border-color: #273149;

            color: #cbd5e1;
        }

        .pagination-wrapper .page-link:hover {
            background: #1e293b;

            color: #ffffff;
        }


        /* =========================================================
       RESPONSIVE
    ========================================================= */

        @media (max-width: 1100px) {

            .videos-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

        }


        @media (max-width: 700px) {

            .page-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .upload-btn {
                width: 100%;
            }

            .videos-grid {
                grid-template-columns:
                    1fr;
            }

        }
    </style>

@endsection