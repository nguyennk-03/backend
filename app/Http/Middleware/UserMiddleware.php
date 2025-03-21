<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Bạn chưa đăng nhập!'], Response::HTTP_UNAUTHORIZED);
        }

        if (Auth::user()->role !== 'user' && Auth::user()->role !== 'admin' ) {
            return response()->json(['message' => 'Bạn không có quyền truy cập!'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
