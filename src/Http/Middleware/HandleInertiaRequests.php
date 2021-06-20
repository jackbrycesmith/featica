<?php

namespace Featica\Http\Middleware;

use Featica\Featica;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'featica::dashboard';

    /**
     * Sets the root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @param Request $request
     * @return string
     */
    public function rootView(Request $request)
    {
        return $this->rootView;
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        if (file_exists($manifest = public_path('vendor/featica/mix-manifest.json'))) {
            return md5_file($manifest);
        }
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'user' => fn () => auth()->user(),
            'features' => fn () => array_values(Featica::definedFeatures()),
            'featica_dashboard' => [
                'app_name' => config('app.name'),
                'version' => fn () => Featica::version(pretty: true),
                'nav_items' => fn () => Featica::navigationItems(),
                'path' => config('featica.path'),
                'timezone' => config('app.timezone'),
                'default_feature_owner' => Featica::getDefaultOwner()
            ]
        ]);
    }
}
