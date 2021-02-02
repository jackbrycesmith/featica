<?php

namespace Featica;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class FeaticaApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
        $this->configureFeatures();
    }

    /**
     * Configure the Featica authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Featica::auth(function ($request) {
            return app()->environment('local') ||
                   Gate::check('viewFeatica', [$request->user()]);
        });
    }

    /**
     * Register the Featica UI gate.
     *
     * This gate determines who can access Featica UI in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewFeatica', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Configure the features flags available within the system.
     *
     * @return void
     */
    protected function configureFeatures()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
