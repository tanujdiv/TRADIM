@extends('layouts.app')

@section('title', 'Notifications - Tradim')

@section('content')

    <div class="tradim-notifications">

        <div class="notification-header">

            <div>
                <h1>Notifications</h1>

                <p>
                    Stay updated with your Tradim activity.
                </p>
            </div>

            @if($notifications->where('is_read', false)->count() > 0)

                <form method="POST" action="{{ route('notifications.readAll') }}">
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

                        <div class="notification-icon">

                            @if($notification->type === 'subscriber')

                                <i class="bi bi-person-plus-fill"></i>

                            @elseif($notification->type === 'comment')

                                <i class="bi bi-chat-left-text-fill"></i>

                            @elseif($notification->type === 'like')

                                <i class="bi bi-hand-thumbs-up-fill"></i>

                            @elseif($notification->type === 'reply')

                                <i class="bi bi-reply-fill"></i>

                            @else

                                <i class="bi bi-bell-fill"></i>

                            @endif

                        </div>


                        <div class="notification-content">

                            <div class="notification-top">

                                <h3>
                                    {{ $notification->title }}
                                </h3>

                                <span>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>

                            </div>


                            @if($notification->message)

                                <p>
                                    {{ $notification->message }}
                                </p>

                            @endif


                            <div class="notification-actions">

                                @if(!$notification->is_read)

                                                <form method="POST" action="{{ route(
                                        'notifications.read',
                                        $notification
                                    ) }}">
                                                    @csrf

                                                    <button type="submit">
                                                        Mark as read
                                                    </button>

                                                </form>

                                @endif


                                <form method="POST" action="{{ route(
                    'notifications.destroy',
                    $notification
                ) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

            @empty

                <div class="empty-notifications">

                    <div class="empty-icon">
                        <i class="bi bi-bell-slash"></i>
                    </div>

                    <h2>No notifications yet</h2>

                    <p>
                        Your notifications will appear here.
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
        .tradim-notifications {
            max-width: 1000px;
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
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .notification-header p {
            color: #8491a7;
            margin: 0;
            font-size: 14px;
        }

        .mark-all-btn {
            border: 1px solid #303b55;
            background: #151c2d;
            color: #dbe4f0;
            padding: 10px 15px;
            border-radius: 9px;
            font-weight: 600;
            cursor: pointer;
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
            display: flex;
            gap: 15px;
            padding: 18px;
            border-radius: 12px;
            background: #10172a;
            border: 1px solid #202a40;
        }

        .notification-item.unread {
            border-color: #49377a;
            background: #141a2e;
        }

        .notification-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #272044;
            color: #a78bfa;
            font-size: 18px;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .notification-top h3 {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .notification-top span {
            color: #64748b;
            font-size: 11px;
            white-space: nowrap;
        }

        .notification-content p {
            color: #aebbd0;
            font-size: 13px;
            line-height: 1.6;
            margin: 7px 0 10px;
        }

        .notification-actions {
            display: flex;
            gap: 12px;
        }

        .notification-actions form {
            margin: 0;
        }

        .notification-actions button {
            border: 0;
            background: transparent;
            padding: 0;
            color: #8b5cf6;
            font-size: 12px;
            cursor: pointer;
        }

        .notification-actions button:hover {
            color: #c4b5fd;
        }

        .empty-notifications {
            text-align: center;
            padding: 80px 20px;
            background: #10172a;
            border: 1px solid #202a40;
            border-radius: 14px;
        }

        .empty-icon {
            font-size: 40px;
            color: #7c3aed;
            margin-bottom: 12px;
        }

        .empty-notifications h2 {
            color: #ffffff;
            font-size: 20px;
            margin-bottom: 6px;
        }

        .empty-notifications p {
            color: #64748b;
            margin: 0;
        }

        .notification-pagination {
            margin-top: 25px;
        }

        @media (max-width: 600px) {

            .notification-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .notification-top {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }

            .notification-item {
                padding: 14px;
            }

        }
    </style>

@endsection