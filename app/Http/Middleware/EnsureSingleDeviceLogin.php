<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EnsureSingleDeviceLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $sessionId = Cache::get('user-session-' . $user->id);
            if ($sessionId && $sessionId !== session()->getId()) {
                Auth::logout();
                return response()->json(['message' => 'Logged out due to login from another device'], 401);
            }
            Cache::put('user-session-' . $user->id, session()->getId(), 3600); // 1 hour
        }

        return $next($request);
    }
}
