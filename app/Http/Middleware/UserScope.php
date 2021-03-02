<?php

namespace App\Http\Middleware;

use Closure;

class UserScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['error' => $roles], 403);
        }
        return $next($request);
    }
}
