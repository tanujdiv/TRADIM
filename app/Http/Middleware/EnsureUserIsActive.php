<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json([
                    'message' => 'Unauthenticated.',
                ], 401)
                : redirect()->guest(route('login'));
        }

        if (!$user->is_active) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account is inactive.',
                ], 403);
            }

            abort(403, 'Your account is inactive.');
        }

        return $next($request);
    }
}