@extends('layouts.app')

@section('content')

    <style>
        .tc-settings-page {
            min-height: calc(100vh - 70px);
            background: #070b18;
            color: #fff;
            padding: 35px 0 60px;
        }

        .tc-settings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .tc-settings-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .tc-settings-title {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
        }

        .tc-settings-subtitle {
            margin: 7px 0 0;
            color: #9ca3af;
            font-size: 14px;
        }

        .tc-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            background: #10172a;
            border: 1px solid #1f2937;
            transition: 0.2s;
        }

        .tc-back-btn:hover {
            color: #fff;
            background: #161f35;
        }

        .tc-alert {
            border: 0;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .tc-card {
            background: #10172a;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .tc-card-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .tc-card-subtitle {
            color: #9ca3af;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .tc-label {
            display: block;
            color: #d1d5db;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .tc-input,
        .tc-textarea {
            width: 100%;
            background: #070b18;
            border: 1px solid #273244;
            border-radius: 10px;
            color: #fff;
            padding: 12px 14px;
            outline: none;
        }

        .tc-input:focus,
        .tc-textarea:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.15);
        }

        .tc-textarea {
            min-height: 140px;
            resize: vertical;
        }

        .tc-input-group {
            position: relative;
        }

        .tc-handle-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .tc-handle-input {
            padding-left: 30px;
        }

        .tc-help {
            color: #6b7280;
            font-size: 12px;
            margin-top: 7px;
        }

        .tc-media-box {
            background: #070b18;
            border: 1px solid #273244;
            border-radius: 14px;
            padding: 18px;
        }

        .tc-avatar-preview {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7c3aed;
            display: block;
            margin-bottom: 15px;
        }

        .tc-avatar-placeholder {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            color: #6b7280;
            margin-bottom: 15px;
            border: 3px solid #374151;
        }

        .tc-banner-preview {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
            margin-bottom: 15px;
            border: 1px solid #273244;
        }

        .tc-banner-placeholder {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            background: linear-gradient(135deg,
                    #10172a,
                    #1e1638);
            border: 1px solid #273244;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .tc-file-input {
            width: 100%;
            background: #10172a;
            border: 1px solid #273244;
            border-radius: 10px;
            color: #d1d5db;
            padding: 8px;
        }

        .tc-file-input::file-selector-button {
            background: #7c3aed;
            border: 0;
            color: #fff;
            padding: 8px 12px;
            border-radius: 7px;
            margin-right: 10px;
            cursor: pointer;
        }

        .tc-remove {
            margin-top: 12px;
            color: #d1d5db;
            font-size: 13px;
        }

        .tc-remove input {
            margin-right: 6px;
        }

        .tc-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .tc-stat {
            background: #070b18;
            border: 1px solid #273244;
            border-radius: 12px;
            padding: 15px;
        }

        .tc-stat-label {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .tc-stat-value {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
        }

        .tc-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .tc-status-verified {
            background: rgba(34, 211, 238, 0.12);
            color: #22d3ee;
        }

        .tc-status-normal {
            background: rgba(156, 163, 175, 0.12);
            color: #9ca3af;
        }

        .tc-preview {
            position: sticky;
            top: 90px;
        }

        .tc-preview-banner {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 14px;
            background: #070b18;
            border: 1px solid #273244;
        }

        .tc-preview-content {
            position: relative;
            padding: 0 18px 20px;
        }

        .tc-preview-avatar {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: -41px;
            border: 4px solid #10172a;
            background: #1f2937;
        }

        .tc-preview-name {
            font-size: 21px;
            font-weight: 700;
            margin-top: 12px;
        }

        .tc-preview-handle {
            color: #9ca3af;
            font-size: 13px;
            margin-top: 3px;
        }

        .tc-preview-description {
            color: #d1d5db;
            font-size: 13px;
            line-height: 1.6;
            margin-top: 15px;
        }

        .tc-preview-stats {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 18px;
            color: #9ca3af;
            font-size: 12px;
        }

        .tc-preview-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 20px;
            color: #c4b5fd;
            text-decoration: none;
            font-size: 13px;
        }

        .tc-preview-link:hover {
            color: #fff;
        }

        .tc-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .tc-btn {
            border: 0;
            border-radius: 10px;
            padding: 11px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .tc-btn-secondary {
            color: #fff;
            background: #1f2937;
        }

        .tc-btn-secondary:hover {
            color: #fff;
            background: #374151;
        }

        .tc-btn-primary {
            color: #fff;
            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);
        }

        .tc-btn-primary:hover {
            color: #fff;
            opacity: 0.92;
        }

        @media (max-width: 767px) {

            .tc-settings-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .tc-settings-title {
                font-size: 25px;
            }

            .tc-stats {
                grid-template-columns: 1fr;
            }

            .tc-preview {
                position: static;
            }

            .tc-settings-page {
                padding-top: 25px;
            }
        }
    </style>

    <div class="tc-settings-page">

        <div class="tc-settings-container">

            {{-- Header --}}
            <div class="tc-settings-header">

                <div>
                    <h1 class="tc-settings-title">
                        Channel Settings
                    </h1>

                    <p class="tc-settings-subtitle">
                        Manage your Tradim channel profile and appearance.
                    </p>
                </div>

                <a href="{{ route('creator.dashboard') }}" class="tc-back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Creator Dashboard
                </a>

            </div>


            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success tc-alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif


            {{-- Error --}}
            @if(session('error'))
                <div class="alert alert-danger tc-alert">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    {{ session('error') }}
                </div>
            @endif


            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger tc-alert">
                    <strong>Please fix the following:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="row g-4">

                {{-- LEFT SIDE --}}
                <div class="col-lg-8">

                    <form action="{{ route('creator.channel.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')


                        {{-- Profile --}}
                        <div class="tc-card">

                            <div class="tc-card-title">
                                Channel Profile
                            </div>

                            <div class="tc-card-subtitle">
                                Update your channel name, handle and description.
                            </div>


                            <div class="mb-4">

                                <label class="tc-label">
                                    Channel Name
                                </label>

                                <input type="text" name="name" class="tc-input @error('name') is-invalid @enderror"
                                    value="{{ old('name', $channel->name) }}" maxlength="100" required>

                            </div>


                            <div class="mb-4">

                                <label class="tc-label">
                                    Channel Handle
                                </label>

                                <div class="tc-input-group">

                                    <span class="tc-handle-prefix">
                                        @
                                    </span>

                                    <input type="text" name="handle"
                                        class="tc-input tc-handle-input @error('handle') is-invalid @enderror"
                                        value="{{ old('handle', $channel->handle) }}" maxlength="50" required>

                                </div>

                                <div class="tc-help">
                                    Only letters, numbers, dots, underscores and hyphens are allowed.
                                </div>

                            </div>


                            <div>

                                <label class="tc-label">
                                    Description
                                </label>

                                <textarea name="description" class="tc-textarea @error('description') is-invalid @enderror"
                                    maxlength="5000">{{ old('description', $channel->description) }}</textarea>

                                <div class="tc-help">
                                    Maximum 5000 characters.
                                </div>

                            </div>

                        </div>


                        {{-- Avatar --}}
                        <div class="tc-card">

                            <div class="tc-card-title">
                                Channel Avatar
                            </div>

                            <div class="tc-card-subtitle">
                                This image represents your channel.
                            </div>

                            <div class="tc-media-box">

                                @if($channel->avatar_url)

                                    <img src="{{ $channel->avatar_url }}" alt="{{ $channel->name }}" class="tc-avatar-preview">

                                @else

                                    <div class="tc-avatar-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>

                                @endif


                                <label class="tc-label">
                                    Upload New Avatar
                                </label>

                                <input type="file" name="avatar" class="tc-file-input @error('avatar') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <div class="tc-help">
                                    JPG, JPEG, PNG or WEBP. Maximum 5MB.
                                </div>


                                @if($channel->avatar)

                                    <label class="tc-remove">
                                        <input type="checkbox" name="remove_avatar" value="1">

                                        Remove current avatar
                                    </label>

                                @endif

                            </div>

                        </div>


                        {{-- Banner --}}
                        <div class="tc-card">

                            <div class="tc-card-title">
                                Channel Banner
                            </div>

                            <div class="tc-card-subtitle">
                                Add a banner to personalize your channel page.
                            </div>

                            <div class="tc-media-box">

                                @if($channel->banner_url)

                                    <img src="{{ $channel->banner_url }}" alt="{{ $channel->name }} banner"
                                        class="tc-banner-preview">

                                @else

                                    <div class="tc-banner-placeholder">
                                        <i class="bi bi-image me-2"></i>
                                        No banner uploaded
                                    </div>

                                @endif


                                <label class="tc-label">
                                    Upload New Banner
                                </label>

                                <input type="file" name="banner" class="tc-file-input @error('banner') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <div class="tc-help">
                                    JPG, JPEG, PNG or WEBP. Maximum 10MB.
                                </div>


                                @if($channel->banner)

                                    <label class="tc-remove">
                                        <input type="checkbox" name="remove_banner" value="1">

                                        Remove current banner
                                    </label>

                                @endif

                            </div>

                        </div>


                        {{-- Statistics --}}
                        <div class="tc-card">

                            <div class="tc-card-title">
                                Channel Statistics
                            </div>

                            <div class="tc-card-subtitle">
                                These values are managed automatically by Tradim.
                            </div>

                            <div class="tc-stats">

                                <div class="tc-stat">
                                    <div class="tc-stat-label">
                                        Subscribers
                                    </div>

                                    <div class="tc-stat-value">
                                        {{ number_format($channel->subscriber_count) }}
                                    </div>
                                </div>


                                <div class="tc-stat">
                                    <div class="tc-stat-label">
                                        Videos
                                    </div>

                                    <div class="tc-stat-value">
                                        {{ number_format($channel->video_count) }}
                                    </div>
                                </div>


                                <div class="tc-stat">
                                    <div class="tc-stat-label">
                                        Total Views
                                    </div>

                                    <div class="tc-stat-value">
                                        {{ number_format($channel->total_views) }}
                                    </div>
                                </div>


                                <div class="tc-stat">

                                    <div class="tc-stat-label">
                                        Verification
                                    </div>

                                    <div class="tc-stat-value">

                                        @if($channel->is_verified)

                                            <span class="tc-status tc-status-verified">
                                                <i class="bi bi-patch-check-fill"></i>
                                                Verified
                                            </span>

                                        @else

                                            <span class="tc-status tc-status-normal">
                                                <i class="bi bi-dash-circle"></i>
                                                Not Verified
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Actions --}}
                        <div class="tc-actions">

                            <a href="{{ route('channels.show', $channel->handle) }}" class="tc-btn tc-btn-secondary">
                                <i class="bi bi-eye"></i>
                                View Channel
                            </a>

                            <button type="submit" class="tc-btn tc-btn-primary">
                                <i class="bi bi-check-lg"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>


                {{-- RIGHT SIDE --}}
                <div class="col-lg-4">

                    <div class="tc-card tc-preview">

                        <div class="tc-card-title">
                            Channel Preview
                        </div>

                        <div class="tc-card-subtitle">
                            Current public appearance.
                        </div>


                        @if($channel->banner_url)

                            <img src="{{ $channel->banner_url }}" alt="" class="tc-preview-banner">

                        @else

                            <div class="tc-banner-placeholder">
                                <i class="bi bi-image"></i>
                            </div>

                        @endif


                        <div class="tc-preview-content">

                            @if($channel->avatar_url)

                                <img src="{{ $channel->avatar_url }}" alt="{{ $channel->name }}" class="tc-preview-avatar">

                            @else

                                <div class="tc-preview-avatar d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person fs-2 text-secondary"></i>
                                </div>

                            @endif


                            <div class="tc-preview-name">
                                {{ $channel->name }}
                            </div>

                            <div class="tc-preview-handle">
                                @{{ $channel->handle }}
                            </div>


                            @if($channel->description)

                                <div class="tc-preview-description">
                                    {{ \Illuminate\Support\Str::limit($channel->description, 180) }}
                                </div>

                            @endif


                            <div class="tc-preview-stats">

                                <span>
                                    {{ number_format($channel->subscriber_count) }}
                                    subscribers
                                </span>

                                <span>
                                    {{ number_format($channel->video_count) }}
                                    videos
                                </span>

                            </div>


                            <a href="{{ route('channels.show', $channel->handle) }}" class="tc-preview-link">
                                Open public channel
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection