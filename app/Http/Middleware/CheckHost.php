<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getHost() !== ['localhost', '127.0.0.0.1',]) {
            return response()->json([
                'status' => false,
                'message' => 'Akses tidak di perbolehkan',
            ]);
        }
        return $next($request);
    }
}