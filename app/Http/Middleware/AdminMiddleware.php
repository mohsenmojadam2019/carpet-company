<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->can('admin.access')) {
            return $request->expectsJson() ? response()->json(['message' => 'Forbidden'], 403) : redirect()->route('admin.login');
        }
        return $next($request);
    }
}
