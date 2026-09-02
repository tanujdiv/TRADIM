<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Notification Page
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $userId = Auth::id();

        $notifications = Notification::query()
            ->where('user_id', $userId)
            ->with('actor')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /*
    |--------------------------------------------------------------------------
    | Mark One Notification As Read
    |--------------------------------------------------------------------------
    */

    public function read(Notification $notification): RedirectResponse
    {
        // User sirf apni notification access kar sakta hai
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($notification->url) {
            return redirect($notification->url);
        }

        return redirect()->route('notifications.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Mark All As Read
    |--------------------------------------------------------------------------
    */

    public function markAllAsRead(): RedirectResponse
    {
        // Notification model ke through direct update karne se IDE warning fix ho jati hai
        Notification::query()
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Notification
    |--------------------------------------------------------------------------
    */

    public function destroy(Notification $notification): RedirectResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}