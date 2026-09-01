<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Notification Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->with('actor')
            ->latest()
            ->paginate(20);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Single Notification As Read
    |--------------------------------------------------------------------------
    */

    public function read(
        Notification $notification
    ): RedirectResponse {

        $this->authorizeNotification($notification);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($notification->url) {
            return redirect()->to(
                $notification->url
            );
        }

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark All Notifications As Read
    |--------------------------------------------------------------------------
    */

    public function readAll(): RedirectResponse
    {
        Notification::query()
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Notification
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Notification $notification
    ): RedirectResponse {

        $this->authorizeNotification($notification);

        $notification->delete();

        return back()->with(
            'success',
            'Notification deleted.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Security Check
    |--------------------------------------------------------------------------
    */

    private function authorizeNotification(
        Notification $notification
    ): void {

        abort_unless(
            $notification->user_id === Auth::id(),
            403
        );
    }
}
