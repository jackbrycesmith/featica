<?php

namespace Featica\Http\Middleware;

use Featica\Featica;

class AuthenticateDashboard
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        return Featica::check($request) ? $next($request) : abort(403);
    }
}
