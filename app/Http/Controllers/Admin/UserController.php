<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(
        Request $request
    ): View {
        $search = trim(
            (string) $request->input('search')
        );

        $users = User::query()
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'username',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact('users', 'search')
        );
    }

    public function toggleStatus(
        Request $request,
        User $user
    ): RedirectResponse {
        if ($user->id === $request->user()->id) {
            return back()->with(
                'error',
                'You cannot deactivate your own admin account.'
            );
        }

        if (
            $user->role === 'admin'
            && $user->is_active
        ) {
            return back()->with(
                'error',
                'An active admin account cannot be deactivated from this panel.'
            );
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return back()->with(
            'success',
            $user->is_active
            ? 'User activated successfully.'
            : 'User deactivated successfully.'
        );
    }

    public function destroy(
        Request $request,
        User $user
    ): RedirectResponse {
        if ($user->id === $request->user()->id) {
            return back()->with(
                'error',
                'You cannot delete your own admin account.'
            );
        }

        if ($user->role === 'admin') {
            return back()->with(
                'error',
                'Admin accounts cannot be deleted from this panel.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User deleted successfully.'
        );
    }
}