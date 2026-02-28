<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Update last_active_at only if it's been more than 5 minutes
            // This prevents excessive database writes
            if ($user->last_active_at === null || $user->last_active_at->diffInMinutes(now()) > 5) {
                $user->last_active_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
