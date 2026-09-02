@extends('layouts.app')

@section('title', 'Notifications - Tradim')

@section('content')

    <div class="tradim-notifications">

        {{-- HEADER --}}
        <div class="notification-header">

            <div>
                <h1>
                    Notifications
                </h1>

                <p>
                    Stay updated with activity on Tradim.
                </p>
            </div>


            @if($notifications->count() > 0)

                <form method="POST" action="{{ route('notifications.readAll') }}">

                    @csrf

                    <button type="submit" class="mark-all-btn">
                        <i class="bi bi-check2-all"></i>
                        Mark all as read
                    </button>

                </form>

            @endif

        </div>


        {{-- NOTIFICATIONS --}}

        <div class="notification-list">

            @forelse($notifications as $notification)

                    <div class="notification-item
                            {{ $notification->read_at ? 'read' : 'unread' }}">

                        {{-- ACTOR AVATAR --}}

                        <div class="notification-avatar">

                            @if($notification->actor)

                                        {{ strtoupper(
                                    substr(
                                        $notification->actor->name,
                                        0,
                                        1
                                    )
                                ) }}

                            @else

                                <i class="bi bi-bell"></i>

                            @endif

                        </div>


                        {{-- CONTENT --}}

                        <div class="notification-content">

                            <div class="notification-title">

                                {{ $notification->title }}

                            </div>


                            <div class="notification-message">

                                {{ $notification->message }}

                            </div>


                            <div class="notification-time">

                                {{ $notification->created_at->diffForHumans() }}

                            </div>


                            {{-- ACTIONS --}}

                            <div class="notification-actions">

                                @if(!$notification->read_at)

                                                <form method="POST" action="{{ route(
                                        'notifications.read',
                                        $notification->id
                                    ) }}">

                                                    @csrf

                                                    <button type="submit" class="notification-btn">
                                                        Mark as read
                                                    </button>

                                                </form>

                                @endif


                                <form method="POST" action="{{ route(
                    'notifications.destroy',
                    $notification->id
                ) }}">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="notification-delete">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>


                        {{-- UNREAD DOT --}}

                        @if(!$notification->read_at)

                            <span class="unread-dot"></span>

                        @endif

                    </div>

            @empty

                <div class="notification-empty">

                    <div class="empty-icon">

                        <i class="bi bi-bell-slash"></i>

                    </div>

                    <h2>
                        No notifications
                    </h2>

                    <p>
                        You're all caught up.
                    </p>

                </div>

            @endforelse

        </div>


        {{-- PAGINATION --}}

        @if($notifications->hasPages())

            <div class="notification-pagination">

                {{ $notifications->links() }}

            </div>

        @endif

    </div>


    <style>
        /* =========================================================
           NOTIFICATIONS
        ========================================================= */

        .tradim-notifications {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 0 50px;
            color: #f8fafc;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .notification-header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .notification-header p {
            color: #8491a7;
            font-size: 13px;
            margin: 0;
        }


        /* =========================================================
           MARK ALL
        ========================================================= */

        .mark-all-btn {
            border: 1px solid #303b55;
            background: #151c2d;
            color: #cbd5e1;
            border-radius: 9px;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .mark-all-btn:hover {
            border-color: #7c3aed;
            color: #ffffff;
        }


        /* =========================================================
           LIST
        ========================================================= */

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        /* =========================================================
           ITEM
        ========================================================= */

        .notification-item {
            position: relative;

            display: flex;
            gap: 14px;

            padding: 17px;

            background: #10172a;

            border: 1px solid #202a40;

            border-radius: 12px;

            transition: .2s;
        }

        .notification-item:hover {
            border-color: #35415e;
        }

        .notification-item.unread {
            background: #121a2d;
            border-color: #303b58;
        }


        /* =========================================================
           AVATAR
        ========================================================= */

        .notification-avatar {
            width: 44px;
            height: 44px;

            flex: 0 0 44px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(135deg,
                    #7c3aed,
                    #ec4899);

            color: #ffffff;

            font-weight: 800;
        }


        /* =========================================================
           CONTENT
        ========================================================= */

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .notification-message {
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.6;
        }

        .notification-time {
            color: #64748b;
            font-size: 11px;
            margin-top: 7px;
        }


        /* =========================================================
           ACTIONS
        ========================================================= */

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .notification-actions form {
            margin: 0;
        }

        .notification-btn,
        .notification-delete {
            border: 0;
            background: transparent;
            padding: 0;

            font-size: 11px;
            cursor: pointer;
        }

        .notification-btn {
            color: #a78bfa;
        }

        .notification-btn:hover {
            color: #c4b5fd;
        }

        .notification-delete {
            color: #ef4444;
        }

        .notification-delete:hover {
            color: #f87171;
        }


        /* =========================================================
           UNREAD DOT
        ========================================================= */

        .unread-dot {
            width: 8px;
            height: 8px;

            flex: 0 0 8px;

            margin-top: 7px;

            border-radius: 50%;

            background: #8b5cf6;
        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .notification-empty {
            padding: 70px 20px;

            text-align: center;

            background: #10172a;

            border: 1px solid #202a40;

            border-radius: 14px;
        }

        .empty-icon {
            font-size: 42px;
            color: #7c3aed;
            margin-bottom: 12px;
        }

        .notification-empty h2 {
            color: #ffffff;
            font-size: 19px;
            margin: 0 0 6px;
        }

        .notification-empty p {
            color: #64748b;
            margin: 0;
            font-size: 13px;
        }


        /* =========================================================
           PAGINATION
        ========================================================= */

        .notification-pagination {
            margin-top: 25px;
        }

        .notification-pagination nav {
            display: flex;
            justify-content: center;
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 600px) {

            .tradim-notifications {
                padding: 10px 0 30px;
            }

            .notification-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .mark-all-btn {
                width: 100%;
            }

            .notification-item {
                padding: 14px;
            }

        }
    </style>

@endsection