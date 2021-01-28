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
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()->hasRole($role)) {
            return response()->json(['Message' => 'Permission Denied.'], 403);
        }
        return $next($request);
    }
}
