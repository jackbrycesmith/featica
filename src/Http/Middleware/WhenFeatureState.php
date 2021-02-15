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
     * @param null|string $abortRedirectUrl The redirect url
     * @param null|string $abortRedirectRoute The redirect named route
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, string $feature, string $state = Feature::STATE_ON, int $abortCode = 403, ?string $abortRedirectUrl = null, ?string $abortRedirectRoute = null)
    {
        $shouldAbort = Featica::stateOf($feature) !== $state;

        if ($shouldAbort) {
            $abortResponse = match(true) {
                empty($abortRedirectUrl) && empty($abortRedirectRoute) => $abortCode,
                !empty($abortRedirectUrl) => redirect(to: $abortRedirectUrl, status: $this->safeRedirectCode($abortCode)),
                !empty($abortRedirectRoute) => redirect(to: route($abortRedirectRoute), status: $this->safeRedirectCode($abortCode)),
            };

            abort($abortResponse);
        }

        return $next($request);
    }

    /**
     * Return a valid redirect status code.
     *
     * @param integer $abortCode The given abort code
     *
     * @return integer A valid redirect status code
     */
    private function safeRedirectCode(int $abortCode): int
    {
        return match(true) {
           ($abortCode >= 300) && ($abortCode < 400) => $abortCode,
           default => 302
        };
    }

    /**
     * Middleware registration helper; when feature 'is'...
     *
     * @param string $feature The feature
     * @param string $state The state
     * @param integer $abortCode The abort code
     * @param null|string $abortRedirectUrl The abort redirect url
     * @param null|string $abortRedirectRoute The abort redirect route
     *
     * @return string
     */
    public static function is(string $feature, string $state = Feature::STATE_ON, int $abortCode = 403, ?string $abortRedirectUrl = null, ?string $abortRedirectRoute = null): string
    {
        $class = static::class;

        return match(true) {
            empty($abortRedirectUrl) && empty($abortRedirectRoute) => "{$class}:{$feature},{$state},{$abortCode}",
            default => "{$class}:{$feature},{$state},{$abortCode},{$abortRedirectUrl},{$abortRedirectRoute}"
        };
    }

    /**
     * Middleware registration helper; when feature is 'on'...
     *
     * @param string $feature The feature
     * @param integer $abortCode The abort code
     * @param null|string $abortRedirectUrl The abort redirect url
     * @param null|string $abortRedirectRoute The abort redirect route
     *
     * @return string
     */
    public static function on(string $feature, int $abortCode = 403, ?string $abortRedirectUrl = null, ?string $abortRedirectRoute = null): string
    {
        return static::is($feature, Feature::STATE_ON, $abortCode, $abortRedirectUrl, $abortRedirectRoute);
    }

    /**
     * Middleware registration helper; when feature is 'off'...
     *
     * @param string $feature The feature
     * @param integer $abortCode The abort response
     * @param null|string $abortRedirectUrl The abort redirect url
     * @param null|string $abortRedirectRoute The abort redirect route
     *
     * @return string
     */
    public static function off(string $feature, int $abortCode = 403, ?string $abortRedirectUrl = null, ?string $abortRedirectRoute = null): string
    {
        return static::is($feature, Feature::STATE_OFF, $abortCode, $abortRedirectUrl, $abortRedirectRoute);
    }
}
