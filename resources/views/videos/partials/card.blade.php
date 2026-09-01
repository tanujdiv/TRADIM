<a href="{{ route('videos.show', $video->slug) }}" class="tradim-video-card">

    {{-- =====================================================
    THUMBNAIL
    ====================================================== --}}

    <div class="video-thumbnail">

        @if($video->thumbnail_path)

            <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="{{ $video->title }}" loading="lazy">

        @else

            <div class="video-thumbnail-placeholder">

                <i class="bi bi-play-fill"></i>

            </div>

        @endif


        {{-- Duration --}}

        @if($video->duration)

            <span class="video-duration">

                {{ gmdate('H:i:s', $video->duration) }}

            </span>

        @endif

    </div>


    {{-- =====================================================
    VIDEO DETAILS
    ====================================================== --}}

    <div class="video-card-info">

        {{-- Channel Avatar --}}

        <div class="video-channel-avatar">

            @if($video->channel?->avatar)

                <img src="{{ asset('storage/' . $video->channel->avatar) }}" alt="{{ $video->channel->name }}"
                    loading="lazy">

            @else

                        {{ strtoupper(
                    substr(
                        $video->channel?->name ?? 'T',
                        0,
                        1
                    )
                ) }}

            @endif

        </div>


        {{-- Information --}}

        <div class="video-text">

            <h3>

                {{ $video->title }}

            </h3>


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

    </div>

</a>


<style>
    /* =========================================================
   VIDEO CARD
========================================================= */

    .tradim-video-card {

        display: block;

        min-width: 0;

        color: inherit !important;

        text-decoration: none !important;

    }


    /* =========================================================
   THUMBNAIL
========================================================= */

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

        background:
            linear-gradient(135deg,
                #171d34,
                #111827);

        color: #8b5cf6;

        font-size: 38px;

    }


    /* =========================================================
   DURATION
========================================================= */

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


    /* =========================================================
   CARD INFO
========================================================= */

    .video-card-info {

        display: flex;

        gap: 11px;

        padding-top: 12px;

    }


    /* =========================================================
   CHANNEL AVATAR
========================================================= */

    .video-channel-avatar {

        flex: 0 0 38px;

        width: 38px;
        height: 38px;

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

        font-size: 13px;

        font-weight: 800;
    }


    .video-channel-avatar img {

        width: 100%;
        height: 100%;

        object-fit: cover;

    }


    /* =========================================================
   TEXT
========================================================= */

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


    /* =========================================================
   HOVER
========================================================= */

    .tradim-video-card:hover .video-text h3 {

        color: #c4b5fd;

    }


    /* =========================================================
   MOBILE
========================================================= */

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