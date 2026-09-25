<div class="tradim-video-card">

    {{-- =====================================================
    THUMBNAIL
    ===================================================== --}}

    <a href="{{ route('videos.show', $video->slug) }}" class="tradim-video-card-link">

        <div class="video-thumbnail">

            @if($video->thumbnail_path)

                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}" loading="lazy">

            @else

                <div class="video-thumbnail-placeholder">
                    <i class="bi bi-play-fill"></i>
                </div>

            @endif

            @if($video->duration)

                <span class="video-duration">
                    {{ gmdate('H:i:s', $video->duration) }}
                </span>

            @endif

        </div>

    </a>


    {{-- =====================================================
    VIDEO DETAILS
    ===================================================== --}}

    <div class="video-card-info">

        {{-- Channel Avatar --}}

        <div class="video-channel-avatar">

            @if($video->channel?->avatar)

                <img src="{{ asset('storage/' . $video->channel->avatar) }}" alt="{{ $video->channel->name }}"
                    loading="lazy">

            @else

                {{ strtoupper(substr($video->channel?->name ?? 'T', 0, 1)) }}

            @endif

        </div>


        {{-- Video Information --}}

        <div class="video-text">

            <a href="{{ route('videos.show', $video->slug) }}" class="video-title-link">

                <h3>{{ $video->title }}</h3>

            </a>


            <p class="video-channel-name">

                {{ $video->channel?->name ?? 'Tradim Creator' }}

                @if($video->channel?->is_verified)

                    <i class="bi bi-patch-check-fill"></i>

                @endif

            </p>


            <p class="video-meta">

                {{ number_format($video->views_count) }} views

                <span>•</span>

                {{ $video->published_at
    ? $video->published_at->diffForHumans()
    : 'Recently'
                }}

            </p>

        </div>


        {{-- =====================================================
        REPORT MENU
        ===================================================== --}}

        @if(!auth()->check() || auth()->id() !== $video->user_id)

            <div class="tradim-video-menu">

                <button type="button" class="tradim-video-menu-btn" aria-label="Video options" aria-haspopup="true"
                    aria-expanded="false" onclick="tradimToggleVideoMenu(this)">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>

                <div class="tradim-video-menu-dropdown">

                    @auth

                                <a href="{{ route('reports.create', [
                            'type' => 'video',
                            'id' => $video->id
                        ]) }}" class="tradim-video-report-link">

                                    <i class="bi bi-flag"></i>

                                    <span>Report video</span>

                                </a>

                    @else

                        <a href="{{ route('login') }}" class="tradim-video-report-link">

                            <i class="bi bi-flag"></i>

                            <span>Log in to report</span>

                        </a>

                    @endauth

                </div>

            </div>

        @endif

    </div>

</div>


@once

    <style>
        /* =====================================================
                VIDEO CARD
            ===================================================== */

        .tradim-video-card {
            position: relative;
            display: block;
            min-width: 0;
            color: inherit;
        }

        .tradim-video-card-link,
        .video-title-link {
            display: block;
            color: inherit !important;
            text-decoration: none !important;
        }


        /* =====================================================
                THUMBNAIL
            ===================================================== */

        .video-thumbnail {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-radius: 12px;
            background: #111827;
            border: 1px solid #202b42;
        }

        .video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .25s ease;
        }

        .tradim-video-card:hover .video-thumbnail img {
            transform: scale(1.04);
        }

        .video-thumbnail-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #171d34, #111827);
            color: #8b5cf6;
            font-size: 38px;
        }

        .video-duration {
            position: absolute;
            right: 8px;
            bottom: 8px;
            padding: 3px 6px;
            border-radius: 5px;
            background: rgba(0, 0, 0, .85);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
        }


        /* =====================================================
                VIDEO DETAILS
            ===================================================== */

        .video-card-info {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding-top: 12px;
        }

        .video-channel-avatar {
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
        }

        .video-channel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-text {
            min-width: 0;
            flex: 1;
        }

        .video-text h3 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            color: #f8fafc;
            font-size: 14px;
            line-height: 1.4;
            font-weight: 700;
            margin: 0 0 6px;
        }

        .video-title-link:hover h3 {
            color: #c4b5fd;
        }

        .video-channel-name {
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.4;
            margin: 0 0 3px;
        }

        .video-channel-name i {
            color: #60a5fa;
            margin-left: 3px;
        }

        .video-meta {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #64748b;
            font-size: 10px;
            margin: 0;
        }


        /* =====================================================
                THREE-DOT REPORT MENU
            ===================================================== */

        .tradim-video-menu {
            position: relative;
            flex: 0 0 30px;
            z-index: 5;
        }

        .tradim-video-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: transparent;
            color: #94a3b8;
            font-size: 18px;
            cursor: pointer;
        }

        .tradim-video-menu-btn:hover,
        .tradim-video-menu-btn[aria-expanded="true"] {
            background: #1e293b;
            color: #ffffff;
        }

        .tradim-video-menu-dropdown {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            width: 175px;
            padding: 6px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #161f36;
            box-shadow: 0 12px 35px rgba(0, 0, 0, .4);
            z-index: 30;
        }

        .tradim-video-menu-dropdown.show {
            display: block;
        }

        .tradim-video-report-link {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px;
            border-radius: 7px;
            color: #fca5a5 !important;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none !important;
            white-space: nowrap;
        }

        .tradim-video-report-link:hover {
            background: rgba(239, 68, 68, .12);
            color: #fecaca !important;
        }


        /* =====================================================
                MOBILE
            ===================================================== */

        @media (max-width: 600px) {

            .video-text h3 {
                font-size: 15px;
            }

            .video-channel-avatar {
                flex-basis: 36px;
                width: 36px;
                height: 36px;
            }

        }
    </style>


    <script>

        function tradimToggleVideoMenu(button) {

            const dropdown = button.nextElementSibling;

            const isOpen = dropdown.classList.contains('show');

            document.querySelectorAll(
                '.tradim-video-menu-dropdown.show'
            ).forEach(function (menu) {

                menu.classList.remove('show');

                const menuButton = menu.previousElementSibling;

                if (menuButton) {
                    menuButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }

            });

            if (!isOpen) {

                dropdown.classList.add('show');

                button.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        }


        document.addEventListener('click', function (event) {

            if (event.target.closest('.tradim-video-menu')) {
                return;
            }

            document.querySelectorAll(
                '.tradim-video-menu-dropdown.show'
            ).forEach(function (menu) {

                menu.classList.remove('show');

                const button = menu.previousElementSibling;

                if (button) {
                    button.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }

            });

        });


        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') {
                return;
            }

            document.querySelectorAll(
                '.tradim-video-menu-dropdown.show'
            ).forEach(function (menu) {

                menu.classList.remove('show');

                const button = menu.previousElementSibling;

                if (button) {
                    button.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }

            });

        });

    </script>

@endonce