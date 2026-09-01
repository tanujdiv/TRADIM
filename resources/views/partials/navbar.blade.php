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
                        $unreadNotifications = Auth::user()
                            ->notifications()
                            ->where('is_read', false)
                            ->count();
                    @endphp

                    <a href="{{ route('notifications.index') }}" class="tradim-notification-bell" title="Notifications">

                        <i class="bi bi-bell"></i>

                        @if($unreadNotifications > 0)

                            <span class="notification-badge">
                                {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                            </span>

                        @endif

                    </a>

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
    .tradim-notification-bell {
        position: relative;
        width: 42px;
        height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        color: #cbd5e1;
        text-decoration: none;

        border-radius: 10px;
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
</style>