@extends('layouts.app')

@section('title', 'Notifications - Tradim')

@section('content')

    <div class="notification-page">

        <div class="notification-header">

            <div>
                <h1>Notifications</h1>
                <p>Stay updated with your Tradim activity.</p>
            </div>

            @if($notifications->where('is_read', false)->count() > 0)

                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf

                    <button type="submit" class="mark-all-btn">
                        <i class="bi bi-check2-all"></i>
                        Mark all as read
                    </button>
                </form>

            @endif

        </div>


        <div class="notification-list">

            @forelse($notifications as $notification)

                <div class="notification-item
                        {{ !$notification->is_read ? 'unread' : '' }}">

                    {{-- ACTOR --}}
                    <div class="notification-avatar">

                        @if($notification->actor)

                            {{ strtoupper(substr($notification->actor->name, 0, 1)) }}

                        @else

                            <i class="bi bi-bell-fill"></i>

                        @endif

                    </div>


                    {{-- CONTENT --}}
                    <a href="{{ route('notifications.read', $notification->id) }}" class="notification-content">

                        <div class="notification-title">

                            @if($notification->type === 'video_like')

                                <i class="bi bi-hand-thumbs-up-fill"></i>

                            @elseif($notification->type === 'new_subscriber')

                                <i class="bi bi-person-plus-fill"></i>

                            @elseif($notification->type === 'video_comment')

                                <i class="bi bi-chat-left-text-fill"></i>

                            @else

                                <i class="bi bi-bell-fill"></i>

                            @endif

                            <strong>
                                {{ $notification->title }}
                            </strong>

                        </div>

                        <p>
                            {{ $notification->message }}
                        </p>

                        <span>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>

                    </a>


                    {{-- DELETE --}}
                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}"
                        class="notification-delete">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="Delete notification">
                            <i class="bi bi-x-lg"></i>
                        </button>

                    </form>

                </div>

            @empty

                <div class="empty-notifications">

                    <i class="bi bi-bell-slash"></i>

                    <h3>No notifications</h3>

                    <p>
                        You're all caught up.
                    </p>

                </div>

            @endforelse

        </div>


        @if($notifications->hasPages())

            <div class="notification-pagination">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>


    <style>
        .notification-page {
            max-width: 900px;
            margin: 0 auto;
            color: #f8fafc;
        }

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .notification-header h1 {
            margin: 0 0 5px;
            font-size: 28px;
            font-weight: 800;
        }

        .notification-header p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        .mark-all-btn {
            border: 1px solid #303b53;
            background: #151c2d;
            color: #cbd5e1;
            padding: 10px 15px;
            border-radius: 9px;
            cursor: pointer;
            font-weight: 600;
        }

        .mark-all-btn:hover {
            border-color: #7c3aed;
            color: #ffffff;
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .notification-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #202a40;
            background: #101827;
            transition: .2s;
        }

        .notification-item:hover {
            border-color: #34415c;
            background: #131d30;
        }

        .notification-item.unread {
            background: #151d31;
            border-color: #3b4661;
        }

        .notification-item.unread::before {
            content: "";
            position: absolute;
            left: 0;
            top: 14px;
            bottom: 14px;
            width: 3px;
            border-radius: 5px;
            background: #7c3aed;
        }

        .notification-avatar {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #7c3aed;
            color: #ffffff;
            font-weight: 800;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
            text-decoration: none;
            color: inherit;
        }

        .notification-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-size: 14px;
        }

        .notification-title i {
            color: #a78bfa;
        }

        .notification-content p {
            margin: 5px 0;
            color: #cbd5e1;
            font-size: 13px;
        }

        .notification-content span {
            color: #64748b;
            font-size: 11px;
        }

        .notification-delete button {
            border: 0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            padding: 8px;
        }

        .notification-delete button:hover {
            color: #ef4444;
        }

        .empty-notifications {
            text-align: center;
            padding: 80px 20px;
            border: 1px solid #202a40;
            border-radius: 14px;
            background: #101827;
        }

        .empty-notifications i {
            font-size: 45px;
            color: #7c3aed;
        }

        .empty-notifications h3 {
            margin: 15px 0 5px;
            color: #ffffff;
        }

        .empty-notifications p {
            margin: 0;
            color: #64748b;
        }

        .notification-pagination {
            margin-top: 25px;
        }

        @media (max-width: 600px) {

            .notification-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .notification-item {
                align-items: flex-start;
            }

            .notification-avatar {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
            }

        }
    </style>

@endsection