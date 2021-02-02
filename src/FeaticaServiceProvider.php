<?php

namespace Featica;

use Featica\Commands\InstallCommand;
use Featica\Commands\PublishCommand;
use Featica\Http\Middleware\ShareInertiaData;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FeaticaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
        $this->registerRoutes();
        $this->shareFeaticaWithInertia();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'featica');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/featica.php', 'featica');

        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);
    }

    /**
     * Register featica's routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'domain' => config('featica.domain', null),
            'prefix' => config('featica.path'),
            'middleware' => config('featica.middleware', Constants::DASHBOARD_MIDDLEWARE),
            'excluded_middleware' => [ShareInertiaData::class]
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../src/Http/featica-dashboard-routes.php');
        });
    }

    /**
     * Register featica's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/featica.php' => config_path('featica.php'),
            ], 'featica-config');

            $this->publishes([
                __DIR__ . '/../stubs/FeaticaServiceProvider.stub' => app_path('Providers/FeaticaServiceProvider.php'),
            ], 'featica-provider');

            $this->publishes([
                __DIR__ . '/../public/vendor/featica' => public_path('vendor/featica'),
            ], 'featica-assets');
        }
    }

    /**
     * Make defined features available to Inertia.
     *
     * @return void
     */
    protected function shareFeaticaWithInertia()
    {
        if (config('jetstream.stack') === 'inertia') {
            $kernel = $this->app->make(Kernel::class);

            $kernel->appendMiddlewareToGroup('web', ShareInertiaData::class);
            $kernel->appendToMiddlewarePriority(ShareInertiaData::class);
        }
    }
}
