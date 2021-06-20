<?php

namespace Featica;

use Closure;
use Featica\Support\Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Featica\Support\FeatureOwningModel;

trait FeaticaManagerDashboard
{
    public function __construct()
    {
        $this->setDefaultFeatureOwningModels();
    }

    /**
     * The default fallback 'owner' for all features, unless otherwise stated.
     *
     * @var ?string
     */
    protected ?string $defaultFeatureOwner = null;

    /**
     * The feature owning models that exist within the application.
     *
     * @var array
     */
    protected array $owningModels = [];

    /**
     * The feature owning models that exist within the application.
     *
     * @var array
     */
    protected array $owningModelDashboardSearch = [
        'App\\Models\\User' => [
            'columns' => ['name', 'email'],
            'relationships' => ['']
        ],
        'App\\Models\\Team' => [
            'columns' => ['name'],
            'relationships' => ['']
        ],
    ];

    /**
     * The feature owning model columns to attempt searching through for the dashboard UI.
     *
     * @var array
     */
    protected array $dashboardModelsDefaultSearchColumns = ['id', 'uuid', 'name', 'email'];

    /**
     * The callback that should be used to authenticate Featica dashboard users.
     *
     * @var ?Closure
     */
    protected ?Closure $authUsing;

    /**
     * Get the definitions of how feature owning models can be search filtered within the dashboard.
     *
     * @return array
     */
    public function getOwningModelDashboardSearch(): array
    {
        return $this->owningModelDashboardSearch;
    }

    /**
     * Set how feature owning models can be search filtered within the dashboard.
     *
     * @param array $items
     * @return void
     */
    public function setOwningModelDashboardSearch(array $items)
    {
        return $this->owningModelDashboardSearch = $items;
    }

    /**
     * Get the default columns for models that can be search filtered within the dashboard.
     *
     * @return array
     */
    public function getDashboardModelsDefaultSearchColumns(): array
    {
        return $this->dashboardModelsDefaultSearchColumns;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function setDefaultFeatureOwningModels()
    {
        if (Helper::uses_feature_flags(class: $userModelClass = 'App\\Models\\User')) {
            $this->setOwningModel(key: 'user', modelClass: $userModelClass, icon: 'heroicons-outline:user');
        }

        if (Helper::uses_feature_flags($teamModelClass = 'App\\Models\\Team')) {
            $this->setOwningModel(key: 'team', modelClass: $teamModelClass, icon: 'heroicons-outline:user-group');
        }
    }

    /**
     * Set the callback that should be used to authenticate Featica dashboard
     * users.
     *
     * @param \Closure $callback
     * @return self
     */
    public function auth(Closure $callback)
    {
        $this->authUsing = $callback;

        return $this;
    }

    /**
     * Determine if the given request can access the Featica dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function check($request)
    {
        return ($this->authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Gets the default feature 'owner'.
     *
     * @return string|null
     */
    public function getDefaultOwner(): ?string
    {
        return $this->defaultFeatureOwner;
    }

    /**
     * Sets the default feature 'owner'.
     *
     * @param $name The name of the owner
     * @return void
     */
    public function setDefaultOwner(?string $name = null)
    {
        $this->defaultFeatureOwner = $name;
    }

    /**
     * Set the feature owning model for visibility in the dashboard.
     *
     * @param string $key
     * @param string $modelClass
     * @return self
     */
    public function setOwningModel(string $key, string $modelClass, ?string $icon = null)
    {
        $this->owningModels[Str::lower(Str::singular($key))] = new FeatureOwningModel(
            className: $modelClass,
            icon: $icon
        );

        return $this;
    }

    /**
     * Resolve the feature flag owning model class for a given key.
     *
     * @param string $key
     * @return string|null
     */
    public function resolveFeatureFlagOwningModel(string $key): ?string
    {
        return Arr::get($this->owningModels, Str::singular($key))?->className;
    }

    /**
     * Get the installed featica version.
     *
     * @param boolean $pretty
     * @param string $packageName
     * @return string|null
     */
    public function version(bool $pretty = false, string $packageName = Constants::PACKAGE_NAME): ?string
    {
        if (! class_exists($composerHelper = \Composer\InstalledVersions::class)) {
            return null;
        }

        if (! $composerHelper::isInstalled($packageName)) {
            return null;
        }

        return $pretty
            ? $composerHelper::getPrettyVersion($packageName)
            : $composerHelper::getVersion($packageName);
    }

    /**
     * Get dashboard navigation items.
     *
     * @return array
     */
    public function navigationItems(): array
    {
        $path = config('featica.path');
        return collect($this->owningModels)->map(function ($item, $key) use ($path) {
            $modelEnding = Str::plural($key);

            return [
                'route' => "/$path/model/$modelEnding",
                'label' => Str::ucfirst($modelEnding),
                'icon' => $item->icon
            ];
        })->values()->all();
    }
}
