<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->guest(route('admin.login'));
        }

        if (!$request->user()->can('admin.access')) {
            abort(403, 'این حساب دسترسی پنل مدیریت ندارد.');
        }

        return $next($request);
    }
}
