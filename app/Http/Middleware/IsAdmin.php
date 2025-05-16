<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated AND is an admin
        if (Auth::check() && Auth::user()->isAdmin()) { // isAdmin() is the helper method in User model
            return $next($request); // User is admin, allow request to proceed
        }

        // User is not authenticated or not an admin
        return response()->json(['message' => 'Forbidden: Admin access required.'], 403);
    }
}