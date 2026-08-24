@extends('layouts.app')

@section('title', 'Edit Video - Tradim')

@section('content')

    <div class="edit-video-page">

        {{-- =========================================================
        HEADER
        ========================================================== --}}

        <div class="edit-header">

            <div>

                <h1>
                    <i class="bi bi-pencil-square"></i>
                    Edit Video
                </h1>

                <p>
                    Update your video information.
                </p>

            </div>

            <a href="{{ route('creator.videos.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i>
                Back to Videos
            </a>

        </div>


        {{-- =========================================================
        VALIDATION ERRORS
        ========================================================== --}}

        @if($errors->any())

            <div class="validation-box">

                <strong>
                    <i class="bi bi-exclamation-triangle"></i>
                    Please fix the following:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
        FORM
        ========================================================== --}}

        <form method="POST" action="{{ route('creator.videos.update', $video->id) }}" enctype="multipart/form-data">

            @csrf

            @method('PUT')


            <div class="edit-layout">


                {{-- =================================================
                LEFT
                ================================================== --}}

                <div class="edit-main">


                    {{-- VIDEO DETAILS --}}

                    <div class="edit-card">

                        <div class="card-title">

                            <h2>
                                Video Details
                            </h2>

                            <p>
                                Basic information about your video.
                            </p>

                        </div>


                        <div class="form-group">

                            <label>
                                Video Title
                            </label>

                            <input type="text" name="title" value="{{ old('title', $video->title) }}" required
                                maxlength="255" placeholder="Enter video title">

                        </div>


                        <div class="form-group">

                            <label>
                                Description
                            </label>

                            <textarea name="description" rows="8" maxlength="5000"
                                placeholder="Tell viewers about your video...">{{ old('description', $video->description) }}</textarea>

                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label>
                                    Category
                                </label>

                                <select name="category_id">

                                    <option value="">
                                        Select Category
                                    </option>

                                    @foreach($categories as $category)

                                        <option value="{{ $category->id }}" @selected(
                                            old(
                                                'category_id',
                                                $video->category_id
                                            ) == $category->id
                                        )>
                                            {{ $category->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="form-group">

                                <label>
                                    Visibility
                                </label>

                                <select name="visibility">

                                    <option value="public" @selected(
                                        old(
                                            'visibility',
                                            $video->visibility
                                        ) === 'public'
                                    )>
                                        Public
                                    </option>

                                    <option value="unlisted" @selected(
                                        old(
                                            'visibility',
                                            $video->visibility
                                        ) === 'unlisted'
                                    )>
                                        Unlisted
                                    </option>

                                    <option value="private" @selected(
                                        old(
                                            'visibility',
                                            $video->visibility
                                        ) === 'private'
                                    )>
                                        Private
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- THUMBNAIL --}}

                    <div class="edit-card">

                        <div class="card-title">

                            <h2>
                                Thumbnail
                            </h2>

                            <p>
                                Upload a new thumbnail or keep the current one.
                            </p>

                        </div>


                        @if($video->thumbnail_path)

                            <div class="current-thumbnail">

                                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}">

                            </div>

                        @endif


                        <div class="form-group">

                            <label>
                                New Thumbnail
                            </label>

                            <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp">

                            <small>
                                JPG, JPEG, PNG or WEBP. Maximum 10 MB.
                            </small>

                        </div>

                    </div>


                    {{-- VIDEO FILE INFO --}}

                    <div class="edit-card">

                        <div class="card-title">

                            <h2>
                                Video File
                            </h2>

                            <p>
                                The original video file is not changed from this page.
                            </p>

                        </div>

                        <div class="file-info">

                            <i class="bi bi-file-earmark-play"></i>

                            <div>

                                <strong>
                                    Current video
                                </strong>

                                <span>
                                    {{ basename($video->video_path) }}
                                </span>

                            </div>

                        </div>

                        <div class="notice">

                            <i class="bi bi-info-circle"></i>

                            To keep video storage safe, video replacement will be handled separately.

                        </div>

                    </div>


                    {{-- ACTIONS --}}

                    <div class="form-actions">

                        <a href="{{ route('creator.videos.index') }}" class="cancel-btn">
                            Cancel
                        </a>

                        <button type="submit" class="save-btn">
                            <i class="bi bi-check-lg"></i>
                            Save Changes
                        </button>

                    </div>

                </div>


                {{-- =================================================
                RIGHT
                ================================================== --}}

                <div class="edit-sidebar">

                    <div class="preview-card">

                        <div class="preview-title">
                            Video Preview
                        </div>

                        <div class="preview-thumbnail">

                            @if($video->thumbnail_path)

                                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}">

                            @else

                                <div class="preview-empty">

                                    <i class="bi bi-play-circle"></i>

                                </div>

                            @endif

                        </div>

                        <h3>
                            {{ $video->title }}
                        </h3>

                        <p>
                            {{ number_format($video->views_count) }} views
                        </p>

                    </div>


                    <div class="tips-card">

                        <h3>
                            <i class="bi bi-lightbulb"></i>
                            Creator Tips
                        </h3>

                        <ul>

                            <li>
                                Keep your title clear and searchable.
                            </li>

                            <li>
                                Use a high-quality thumbnail.
                            </li>

                            <li>
                                Write a useful description.
                            </li>

                            <li>
                                Choose the correct category.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <style>
        /* =========================================================
       PAGE
    ========================================================= */

        .edit-video-page {
            color: #f8fafc;
        }


        /* =========================================================
       HEADER
    ========================================================= */

        .edit-header {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;
        }

        .edit-header h1 {
            margin: 0 0 7px;

            color: #ffffff;

            font-size: 28px;

            font-weight: 800;
        }

        .edit-header h1 i {
            color: #8b5cf6;
        }

        .edit-header p {
            margin: 0;

            color: #94a3b8;

            font-size: 14px;
        }

        .back-btn {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 10px 15px;

            border-radius: 9px;

            background: #151c2d;

            border: 1px solid #2a354c;

            color: #cbd5e1 !important;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;
        }

        .back-btn:hover {
            border-color: #7c3aed;

            color: #ffffff !important;
        }


        /* =========================================================
       VALIDATION
    ========================================================= */

        .validation-box {
            margin-bottom: 20px;

            padding: 15px 18px;

            border-radius: 10px;

            background: rgba(239, 68, 68, .10);

            border: 1px solid rgba(239, 68, 68, .25);

            color: #fca5a5;
        }

        .validation-box strong {
            color: #fca5a5;
        }

        .validation-box ul {
            margin: 8px 0 0;

            padding-left: 20px;

            color: #fda4af;

            font-size: 13px;
        }


        /* =========================================================
       LAYOUT
    ========================================================= */

        .edit-layout {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr) 320px;

            gap: 22px;

            align-items: start;
        }


        /* =========================================================
       CARD
    ========================================================= */

        .edit-card,
        .preview-card,
        .tips-card {
            background: #121a2b;

            border: 1px solid #273149;

            border-radius: 14px;
        }

        .edit-card {
            padding: 22px;

            margin-bottom: 20px;
        }

        .card-title {
            margin-bottom: 22px;

            padding-bottom: 15px;

            border-bottom: 1px solid #222c40;
        }

        .card-title h2 {
            margin: 0 0 5px;

            color: #ffffff;

            font-size: 18px;

            font-weight: 800;
        }

        .card-title p {
            margin: 0;

            color: #64748b;

            font-size: 12px;
        }


        /* =========================================================
       FORM
    ========================================================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: #e2e8f0;

            font-size: 13px;

            font-weight: 700;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;

            padding: 11px 13px;

            background: #0d1423;

            border: 1px solid #2a354c;

            border-radius: 9px;

            color: #ffffff;

            outline: none;

            font-size: 13px;
        }

        .form-group textarea {
            resize: vertical;

            min-height: 150px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #7c3aed;

            box-shadow:
                0 0 0 3px rgba(124, 58, 237, .10);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #64748b;
        }

        .form-group small {
            display: block;

            margin-top: 7px;

            color: #64748b;

            font-size: 11px;
        }

        .form-row {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 15px;
        }


        /* =========================================================
       THUMBNAIL
    ========================================================= */

        .current-thumbnail {
            width: 100%;

            max-width: 600px;

            margin-bottom: 18px;

            aspect-ratio: 16 / 9;

            overflow: hidden;

            border-radius: 10px;

            background: #070b13;
        }

        .current-thumbnail img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }


        /* =========================================================
       FILE INFO
    ========================================================= */

        .file-info {
            display: flex;

            align-items: center;

            gap: 13px;

            padding: 14px;

            border-radius: 10px;

            background: #0d1423;

            border: 1px solid #222c40;
        }

        .file-info>i {
            color: #8b5cf6;

            font-size: 28px;
        }

        .file-info strong {
            display: block;

            color: #ffffff;

            font-size: 13px;
        }

        .file-info span {
            display: block;

            margin-top: 4px;

            color: #64748b;

            font-size: 11px;

            word-break: break-all;
        }

        .notice {
            display: flex;

            gap: 8px;

            margin-top: 13px;

            padding: 11px;

            border-radius: 8px;

            background: rgba(59, 130, 246, .08);

            color: #93c5fd;

            font-size: 11px;

            line-height: 1.5;
        }


        /* =========================================================
       PREVIEW
    ========================================================= */

        .preview-card {
            padding: 18px;

            margin-bottom: 20px;
        }

        .preview-title {
            margin-bottom: 13px;

            color: #ffffff;

            font-size: 15px;

            font-weight: 800;
        }

        .preview-thumbnail {
            width: 100%;

            aspect-ratio: 16 / 9;

            overflow: hidden;

            border-radius: 9px;

            background: #070b13;

            margin-bottom: 13px;
        }

        .preview-thumbnail img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        .preview-empty {
            width: 100%;
            height: 100%;

            display: flex;

            align-items: center;
            justify-content: center;

            color: #8b5cf6;

            font-size: 40px;
        }

        .preview-card h3 {
            margin: 0 0 5px;

            color: #ffffff;

            font-size: 14px;

            line-height: 1.4;
        }

        .preview-card p {
            margin: 0;

            color: #64748b;

            font-size: 11px;
        }


        /* =========================================================
       TIPS
    ========================================================= */

        .tips-card {
            padding: 18px;
        }

        .tips-card h3 {
            margin: 0 0 14px;

            color: #ffffff;

            font-size: 15px;
        }

        .tips-card h3 i {
            color: #fbbf24;
        }

        .tips-card ul {
            margin: 0;

            padding-left: 18px;
        }

        .tips-card li {
            margin-bottom: 10px;

            color: #94a3b8;

            font-size: 12px;

            line-height: 1.5;
        }


        /* =========================================================
       ACTIONS
    ========================================================= */

        .form-actions {
            display: flex;

            justify-content: flex-end;

            gap: 10px;
        }

        .cancel-btn,
        .save-btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 11px 18px;

            border-radius: 9px;

            font-size: 13px;

            font-weight: 700;

            text-decoration: none;

            cursor: pointer;
        }

        .cancel-btn {
            background: #151c2d;

            border: 1px solid #2a354c;

            color: #cbd5e1 !important;
        }

        .cancel-btn:hover {
            color: #ffffff !important;

            border-color: #475569;
        }

        .save-btn {
            border: 0;

            background: #7c3aed;

            color: #ffffff;
        }

        .save-btn:hover {
            background: #6d28d9;
        }


        /* =========================================================
       RESPONSIVE
    ========================================================= */

        @media (max-width: 1000px) {

            .edit-layout {
                grid-template-columns: 1fr;
            }

            .edit-sidebar {
                order: -1;
            }

        }

        @media (max-width: 650px) {

            .edit-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .back-btn {
                width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .cancel-btn,
            .save-btn {
                width: 100%;
            }

        }
    </style>

@endsection