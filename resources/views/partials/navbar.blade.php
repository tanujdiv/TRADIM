<nav class="tradim-navbar">

    <div class="tradim-navbar-left">

        <button class="sidebar-toggle" id="sidebarToggle" type="button">
            <i class="bi bi-list"></i>
        </button>


        <a href="{{ route('home') }}" class="tradim-logo">

            <span class="logo-symbol">
                ∞
            </span>

            <span class="logo-text">
                TRADIM
            </span>

        </a>

    </div>



    <div class="tradim-search-wrapper">

        <form method="GET" action="{{ route('search') }}" class="tradim-search-form">

            <i class="bi bi-search"></i>

            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search videos, creators..."
                autocomplete="off">

        </form>


        <button class="voice-search" type="button">

            <i class="bi bi-mic-fill"></i>

        </button>

    </div>



    <div class="tradim-navbar-right">


        @auth

                <!-- CREATE -->

                <a href="{{ route('creator.dashboard') }}" class="nav-action create-action">

                    <i class="bi bi-plus-lg"></i>

                    <span>
                        Create
                    </span>

                </a>


                @auth

                    @php
                        $notificationItems = Auth::user()
                            ->notifications()
                            ->with('actor')
                            ->latest()
                            ->take(5)
                            ->get();

                        $unreadNotifications = Auth::user()
                            ->notifications()
                            ->where('is_read', false)
                            ->count();
                    @endphp

                    <div class="tradim-notification-wrapper">

                        <button type="button" class="tradim-notification-bell" id="tradimNotificationToggle" title="Notifications">

                            <i class="bi bi-bell"></i>

                            @if($unreadNotifications > 0)
                                <span class="notification-badge">
                                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                                </span>
                            @endif

                        </button>


                        {{-- NOTIFICATION DROPDOWN --}}
                        <div class="tradim-notification-dropdown" id="tradimNotificationDropdown">

                            <div class="notification-dropdown-header">

                                <div>
                                    <strong>Notifications</strong>

                                    @if($unreadNotifications > 0)
                                        <small>
                                            {{ $unreadNotifications }} unread
                                        </small>
                                    @endif
                                </div>

                                @if($unreadNotifications > 0)

                                    <form method="POST" action="{{ route('notifications.read-all') }}">

                                        @csrf

                                        <button type="submit" class="mark-all-btn">
                                            Mark all as read
                                        </button>

                                    </form>

                                @endif

                            </div>


                            <div class="notification-dropdown-body">

                                @forelse($notificationItems as $notification)

                                    <a href="{{ route('notifications.read', $notification) }}"
                                        class="notification-item {{ !$notification->is_read ? 'notification-unread' : '' }}">

                                        {{-- ACTOR --}}
                                        <div class="notification-avatar">

                                            @if($notification->actor)
                                                {{ strtoupper(substr($notification->actor->name, 0, 1)) }}
                                            @else
                                                <i class="bi bi-person"></i>
                                            @endif

                                        </div>


                                        {{-- CONTENT --}}
                                        <div class="notification-content">

                                            <div class="notification-title">

                                                @if($notification->type === 'video_like')

                                                    <i class="bi bi-heart-fill text-danger"></i>

                                                @elseif($notification->type === 'video_comment')

                                                    <i class="bi bi-chat-fill text-info"></i>

                                                @elseif($notification->type === 'new_subscriber')

                                                    <i class="bi bi-person-plus-fill text-success"></i>

                                                @elseif($notification->type === 'new_video')

                                                    <i class="bi bi-play-circle-fill text-primary"></i>

                                                @else

                                                    <i class="bi bi-bell-fill"></i>

                                                @endif

                                                {{ $notification->title }}

                                            </div>

                                            <p>
                                                {{ $notification->message }}
                                            </p>

                                            <small>
                                                {{ $notification->created_at?->diffForHumans() }}
                                            </small>

                                        </div>


                                        {{-- UNREAD DOT --}}
                                        @if(!$notification->is_read)
                                            <span class="notification-unread-dot"></span>
                                        @endif

                                    </a>

                                @empty

                                    <div class="notification-empty">

                                        <i class="bi bi-bell-slash fs-2"></i>

                                        <p class="mb-0 mt-2">
                                            No notifications yet.
                                        </p>

                                    </div>

                                @endforelse

                            </div>


                            <div class="notification-dropdown-footer">

                                <a href="{{ route('notifications.index') }}">
                                    View all notifications
                                </a>

                            </div>

                        </div>

                    </div>

                @endauth

                <!-- PROFILE -->

                <a href="{{ route('account') }}" class="nav-profile">

                    <div class="profile-avatar">

                        {{ strtoupper(
                substr(Auth::user()->name, 0, 1)
            ) }}

                    </div>

                </a>


        @else


            <!-- LOGIN -->

            <a href="{{ route('login') }}" class="nav-action">

                <i class="bi bi-box-arrow-in-right"></i>

                <span>
                    Sign In
                </span>

            </a>


            <!-- REGISTER -->

            <a href="{{ route('register') }}" class="nav-action create-action">

                <i class="bi bi-person-plus"></i>

                <span>
                    Join Tradim
                </span>

            </a>


        @endauth


    </div>

</nav>

<style>
    .tradim-notification-wrapper {
        position: relative;
    }

    .tradim-notification-bell {
        position: relative;
        width: 42px;
        height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        color: #cbd5e1;
        background: transparent;
        border: 0;

        text-decoration: none;
        border-radius: 10px;

        cursor: pointer;
    }

    .tradim-notification-bell:hover {
        background: #151c2d;
        color: #ffffff;
    }

    .tradim-notification-bell i {
        font-size: 19px;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 1px;

        min-width: 18px;
        height: 18px;

        padding: 0 5px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 20px;

        background: #ef4444;
        color: #ffffff;

        font-size: 9px;
        font-weight: 800;

        border: 2px solid #070b18;
    }


    /* DROPDOWN */

    .tradim-notification-dropdown {
        position: absolute;

        top: calc(100% + 10px);
        right: 0;

        width: 390px;

        background: #10172a;
        border: 1px solid #26304a;
        border-radius: 14px;

        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);

        overflow: hidden;

        display: none;

        z-index: 9999;
    }

    .tradim-notification-dropdown.show {
        display: block;
    }


    /* HEADER */

    .notification-dropdown-header {
        padding: 16px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        border-bottom: 1px solid #26304a;
    }

    .notification-dropdown-header strong {
        color: #ffffff;
        font-size: 16px;
    }

    .notification-dropdown-header small {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 12px;
    }

    .mark-all-btn {
        border: 0;
        background: transparent;
        color: #a78bfa;

        font-size: 12px;
        font-weight: 600;

        cursor: pointer;
    }

    .mark-all-btn:hover {
        color: #c4b5fd;
    }


    /* BODY */

    .notification-dropdown-body {
        max-height: 430px;
        overflow-y: auto;
    }


    /* ITEM */

    .notification-item {
        position: relative;

        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px 16px;

        color: inherit;
        text-decoration: none;

        border-bottom: 1px solid rgba(148, 163, 184, 0.08);

        transition: background 0.2s ease;
    }

    .notification-item:hover {
        background: #151e34;
    }

    .notification-unread {
        background: rgba(124, 58, 237, 0.08);
    }


    /* AVATAR */

    .notification-avatar {
        width: 38px;
        height: 38px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: linear-gradient(135deg,
                #7c3aed,
                #ec4899);

        color: #ffffff;

        font-size: 14px;
        font-weight: 700;
    }


    /* CONTENT */

    .notification-content {
        min-width: 0;
        padding-right: 10px;
    }

    .notification-title {
        color: #ffffff;

        font-size: 13px;
        font-weight: 700;

        display: flex;
        align-items: center;
        gap: 6px;
    }

    .notification-content p {
        margin: 4px 0;

        color: #cbd5e1;

        font-size: 12px;
        line-height: 1.45;
    }

    .notification-content small {
        color: #64748b;
        font-size: 10px;
    }


    /* UNREAD DOT */

    .notification-unread-dot {
        position: absolute;

        top: 20px;
        right: 12px;

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #7c3aed;
    }


    /* EMPTY */

    .notification-empty {
        padding: 40px 20px;

        text-align: center;

        color: #64748b;
    }


    /* FOOTER */

    .notification-dropdown-footer {
        padding: 12px 16px;

        text-align: center;

        border-top: 1px solid #26304a;
    }

    .notification-dropdown-footer a {
        color: #a78bfa;

        font-size: 13px;
        font-weight: 600;

        text-decoration: none;
    }

    .notification-dropdown-footer a:hover {
        color: #c4b5fd;
    }


    /* MOBILE */

    @media (max-width: 576px) {

        .tradim-notification-dropdown {
            position: fixed;

            top: 65px;
            left: 10px;
            right: 10px;

            width: auto;
        }

    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const toggle = document.getElementById(
            'tradimNotificationToggle'
        );

        const dropdown = document.getElementById(
            'tradimNotificationDropdown'
        );

        if (!toggle || !dropdown) {
            return;
        }

        toggle.addEventListener('click', function (event) {

            event.stopPropagation();

            dropdown.classList.toggle('show');

        });


        dropdown.addEventListener('click', function (event) {

            event.stopPropagation();

        });


        document.addEventListener('click', function () {

            dropdown.classList.remove('show');

        });

    });
</script>