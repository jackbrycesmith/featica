<?php

namespace Featica\Http\Middleware;

use Featica\Featica;
use Inertia\Inertia;

class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        Inertia::share('featica', fn() => Featica::inertiaShareData());

        return $next($request);
    }
}
