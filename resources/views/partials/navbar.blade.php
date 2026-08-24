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


                <!-- NOTIFICATION -->

                <a href="#" class="nav-action notification-action">

                    <i class="bi bi-bell"></i>

                    <span class="notification-dot">
                        3
                    </span>

                </a>


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