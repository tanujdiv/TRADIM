<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin') - Tradim
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --tradim-bg: #070b18;
            --tradim-sidebar: #0b1020;
            --tradim-card: #10172a;
            --tradim-hover: #161f36;
            --tradim-border: rgba(255, 255, 255, .08);
            --tradim-purple: #7c3aed;
            --tradim-pink: #ec4899;
            --tradim-blue: #2563eb;
            --tradim-cyan: #22d3ee;
            --tradim-text: #f8fafc;
            --tradim-muted: #94a3b8;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--tradim-bg);
            color: var(--tradim-text);
        }

        .tradim-admin {
            min-height: 100vh;
            display: flex;
        }

        .tradim-sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--tradim-sidebar);
            border-right: 1px solid var(--tradim-border);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .tradim-brand {
            padding: 24px 20px;
            font-size: 24px;
            font-weight: 800;
            border-bottom: 1px solid var(--tradim-border);
        }

        .tradim-brand span {
            color: var(--tradim-purple);
        }

        .tradim-nav {
            padding: 18px 12px;
        }

        .tradim-nav a {
            display: block;
            color: var(--tradim-muted);
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: .2s;
        }

        .tradim-nav a:hover,
        .tradim-nav a.active {
            color: #fff;
            background: var(--tradim-hover);
        }

        .tradim-main {
            width: calc(100% - 250px);
            margin-left: 250px;
            min-height: 100vh;
        }

        .tradim-topbar {
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            border-bottom: 1px solid var(--tradim-border);
            background: var(--tradim-bg);
        }

        .tradim-content {
            padding: 28px;
        }

        .tradim-card {
            background: var(--tradim-card);
            border: 1px solid var(--tradim-border);
            border-radius: 14px;
        }

        .tradim-stat {
            padding: 22px;
            height: 100%;
        }

        .tradim-stat-label {
            color: var(--tradim-muted);
            font-size: 14px;
        }

        .tradim-stat-value {
            font-size: 30px;
            font-weight: 800;
            margin-top: 7px;
        }

        .tradim-table {
            color: #fff;
            margin-bottom: 0;
        }

        .tradim-table th {
            color: var(--tradim-muted);
            font-weight: 600;
            border-color: var(--tradim-border);
            white-space: nowrap;
        }

        .tradim-table td {
            border-color: var(--tradim-border);
            vertical-align: middle;
        }

        .tradim-table tbody tr:hover {
            background: rgba(255, 255, 255, .025);
        }

        .tradim-muted {
            color: var(--tradim-muted);
        }

        .tradim-input {
            background: #0b1020 !important;
            color: #fff !important;
            border: 1px solid var(--tradim-border) !important;
        }

        .tradim-input:focus {
            border-color: var(--tradim-purple) !important;
            box-shadow: none !important;
        }

        .tradim-pagination .pagination {
            margin-bottom: 0;
        }

        .tradim-pagination .page-link {
            background: var(--tradim-card);
            color: #fff;
            border-color: var(--tradim-border);
        }

        .tradim-pagination .active .page-link {
            background: var(--tradim-purple);
            border-color: var(--tradim-purple);
        }

        .tradim-danger {
            color: #f87171;
        }

        @media (max-width: 991px) {
            .tradim-sidebar {
                width: 210px;
            }

            .tradim-main {
                width: calc(100% - 210px);
                margin-left: 210px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="tradim-admin">

        <aside class="tradim-sidebar">

            <div class="tradim-brand">
                Tra<span>dim</span>
                <div class="small tradim-muted">
                    Admin Panel
                </div>
            </div>

            <nav class="tradim-nav">

                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    Users
                </a>

                <a href="{{ route('admin.videos.index') }}"
                    class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                    Videos
                </a>

                <a href="{{ route('admin.channels.index') }}"
                    class="{{ request()->routeIs('admin.channels.*') ? 'active' : '' }}">
                    Channels
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    Categories
                </a>

                <a href="{{ route('admin.comments.index') }}"
                    class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    Comments
                </a>

                <hr class="border-secondary opacity-25">

                <a href="{{ route('home') }}">
                    View Tradim
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf

                    <button type="submit" class="btn btn-link text-danger text-decoration-none w-100 text-start px-3">
                        Logout
                    </button>
                </form>

            </nav>

        </aside>

        <main class="tradim-main">

            <header class="tradim-topbar">

                <div>
                    <strong>
                        @yield('page_title', 'Dashboard')
                    </strong>
                </div>

                <div class="tradim-muted">
                    {{ auth()->user()->name }}
                </div>

            </header>

            <section class="tradim-content">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

            </section>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>