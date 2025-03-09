<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem user đã đăng nhập hay chưa
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Kiểm tra quyền của user
        if (Auth::user()->role !== 'user') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
