<?php

namespace App\Http\Middleware;

use Closure;

class MyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('bearer') !== null) {
            $request->headers->set(
                'Authorization',
                "Bearer {$request->input('bearer')}");
        }
        return $next($request);
    }
}
