<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        if (!$user->is_active) {
            abort(403, 'Your account is inactive.');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}