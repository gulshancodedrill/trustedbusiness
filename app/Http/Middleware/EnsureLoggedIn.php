<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureLoggedIn
{
    /**
     * Allow access only to authenticated users.
     *
     * Note: This middleware does NOT check role; any authenticated user is allowed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}

