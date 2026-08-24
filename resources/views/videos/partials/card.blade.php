<a href="{{ route(
    'videos.show',
    $video->slug
) }}" class="tradim-video-card">


    {{-- THUMBNAIL --}}

    <div class="video-thumbnail">

        @if($video->thumbnail_path)

                <img src="{{ asset(
                'storage/' .
                $video->thumbnail_path
            ) }}" alt="{{ $video->title }}" loading="lazy">

        @else

            <div class="video-thumbnail-default">

                <i class="bi bi-play-fill"></i>

            </div>

        @endif


        {{-- DURATION --}}

        @if($video->duration)

                <span class="video-duration">

                    {{ gmdate(
                'H:i:s',
                $video->duration
            ) }}

                </span>

        @endif

    </div>



    {{-- VIDEO DETAILS --}}

    <div class="video-card-details">


        <div class="video-card-avatar">

            @if(
                            $video->channel &&
                            $video->channel->avatar
                        )

                        <img src="{{ asset(
                    'storage/' .
                    $video->channel->avatar
                ) }}" alt="{{ $video->channel->name }}">

            @else

                        {{ strtoupper(
                    substr(
                        $video->channel->name ?? 'T',
                        0,
                        1
                    )
                ) }}

            @endif

        </div>


        <div class="video-card-content">

            <h3>

                {{ $video->title }}

            </h3>


            <p class="video-channel-name">

                {{ $video->channel->name ?? 'Tradim Creator' }}

                @if(
                        $video->channel &&
                        $video->channel->is_verified
                    )

                    <i class="bi bi-patch-check-fill"></i>

                @endif

            </p>


            <p class="video-stats">

                {{ number_format(
    $video->views_count
) }}

                views

                •

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

        color: inherit;

        text-decoration: none;

    }


    .video-thumbnail {

        position: relative;

        width: 100%;

        aspect-ratio: 16 / 9;

        overflow: hidden;

        border-radius: 12px;

        background: #151c2d;

        border: 1px solid #273149;

    }


    .video-thumbnail img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        transition: transform .25s ease;

    }


    .tradim-video-card:hover .video-thumbnail img {

        transform: scale(1.04);

    }


    .video-thumbnail-default {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
            linear-gradient(135deg,
                #171d32,
                #241c3c);

        color: #8b5cf6;

        font-size: 42px;

    }


    .video-duration {

        position: absolute;

        right: 8px;

        bottom: 8px;

        padding: 3px 6px;

        border-radius: 4px;

        background: rgba(0,
                0,
                0,
                .8);

        color: #ffffff;

        font-size: 10px;

        font-weight: 700;

    }


    /* =========================================================
   CARD DETAILS
========================================================= */

    .video-card-details {

        display: flex;

        gap: 11px;

        padding-top: 12px;

    }


    .video-card-avatar {

        width: 36px;

        height: 36px;

        flex: 0 0 36px;

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

        font-size: 12px;

        font-weight: 800;

    }


    .video-card-avatar img {

        width: 100%;

        height: 100%;

        object-fit: cover;

    }


    .video-card-content {

        min-width: 0;

    }


    .video-card-content h3 {

        color: #f8fafc;

        font-size: 14px;

        line-height: 1.4;

        font-weight: 700;

        margin: 0 0 6px;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }


    .video-channel-name {

        color: #94a3b8;

        font-size: 11px;

        margin: 0 0 3px;

    }


    .video-channel-name i {

        color: #60a5fa;

    }


    .video-stats {

        color: #64748b;

        font-size: 10px;

        margin: 0;

    }
</style>