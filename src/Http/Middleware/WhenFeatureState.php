<?php

namespace Featica\Http\Middleware;

use Closure;
use Featica\Featica;
use Featica\Feature;
use Illuminate\Http\Request;

class WhenFeatureState
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request The request
     * @param callable $next
     * @param string $feature The feature
     * @param string $state The state
     * @param integer $abortCode The abort code
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, string $feature, string $state = Feature::STATE_ON, int $abortCode = 403)
    {
        abort_if(Featica::stateOf($feature) !== $state, $abortCode);

        return $next($request);
    }

    /**
     * Middleware registration helper; when feature 'is'...
     *
     * @param string $feature The feature
     * @param string $state The state
     * @param integer $abortCode The abort code
     *
     * @return string
     */
    public static function is(string $feature, string $state = Feature::STATE_ON, int $abortCode = 403): string
    {
        $class = static::class;

        return "{$class}:{$feature},{$state},{$abortCode}";
    }

    /**
     * Middleware registration helper; when feature is 'on'...
     *
     * @param string $feature The feature
     * @param integer $abortCode The abort code
     *
     * @return string
     */
    public static function on(string $feature, int $abortCode = 403): string
    {
        return static::is($feature, Feature::STATE_ON, $abortCode);
    }

    /**
     * Middleware registration helper; when feature is 'off'...
     *
     * @param string $feature The feature
     * @param integer $abortCode The abort code
     *
     * @return string ( description_of_the_return_value )
     */
    public static function off(string $feature, int $abortCode = 403): string
    {
        return static::is($feature, Feature::STATE_OFF, $abortCode);
    }
}
