<?php

namespace Featica;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Throwable;

class FeaticaManager
{
    /**
     * The features that exist within the application.
     *
     * @var array
     */
    protected array $features = [];

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
    protected array $owningModels = [
        'user' => 'App\\Models\\User',
        'team' => 'App\\Models\\Team',
    ];

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
     * Define a feature flag.
     *
     * @param Feature $feature
     * @return \Featica\Feature
     */
    public function add(Feature $feature)
    {
        $this->features[$feature->key] = $feature;

        return $feature;
    }

    /**
     * Get all the features that have been defined.
     *
     * @return array
     */
    public function definedFeatures(): array
    {
        return $this->features;
    }

    /**
     * Clear all features that have been defined.
     *
     * @return void
     */
    public function clearDefinedFeatures()
    {
        $this->features = [];
    }

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
     * Searches for the first match.
     *
     * @param string $feature The feature
     * @return \Featica\Feature|null
     */
    public function find(string $feature): ?Feature
    {
        return Arr::get($this->features, $feature);
    }

    /**
     * Get the state of the given feature key.
     *
     * @param string $feature The feature
     * @param null|string $fallbackState The fallback state if the feature is not found.
     * @return ?string
     */
    public function stateOf(string $feature, ?string $fallbackState = Feature::STATE_OFF): ?string
    {
        return $this->find($feature)?->state ?? $fallbackState;
    }

    /**
     * Check whether the given model has a given feature enabled.
     *
     * @param mixed $model The model
     * @param string $featureKey The feature key
     * @return boolean true if enabled, otherwise false
     */
    public function modelHasFeature($model, string $featureKey): bool
    {
        $feature = $this->find($featureKey);
        if (empty($feature)) {
            return false;
        }

        if ($feature->type === Featica::TYPE_GLOBAL) {
            return $feature->isEnabled();
        }

        try {
            if ($modelFeatureFlags = $model->feature_flags) {
                if ($state = Arr::get($modelFeatureFlags, $featureKey)) {
                    return $state === Feature::STATE_ON;
                }
            }
        } catch (Throwable $e) {

        }

        return $feature->isEnabled();
    }

    /**
     * Get the shared inertia data.
     *
     * @return array
     */
    public function inertiaShareData(): array
    {
        return [
            'features' => array_map(fn($feature) => $feature->shareableData(), $this->features)
        ];
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
     * Decide what to return for a given model & feature.
     *
     * @param string $feature The feature
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param Closure $on The enabled
     * @param Closure $disabled The disabled
     * @return mixed
     */
    public function when(string $feature, ?Model $model = null, mixed $on = null, mixed $off = null)
    {
        if (empty($model)) {
            return $this->evaluate($this->isEnabled($feature), $on, $off);
        }

        return $this->evaluate($this->modelHasFeature($model, $feature), $on, $off);
    }

    /**
     * Return whenTrue or whenFalse for a given match.
     *
     * @param boolean $match The match
     * @param mixed $whenTrue The value to return when true
     * @param mixed $whenFalse The value to return when false
     * @return mixed
     */
    protected static function evaluate(bool $match, mixed $whenTrue, mixed $whenFalse)
    {
        return match($match) {
            true => ($whenTrue instanceof Closure) ? App::call($whenTrue) : $whenTrue,
            false => ($whenFalse instanceof Closure) ? App::call($whenFalse) : $whenFalse,
        };
    }

    /**
     * Determines whether the specified feature key is enabled.
     *
     * @param string $featureKey The feature key
     * @return boolean True if the specified feature key is enabled, False otherwise.
     */
    public function isEnabled(string $featureKey): bool
    {
        $feature = $this->find($featureKey);

        if (empty($feature)) {
            return false;
        }

        return $feature->isEnabled();
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
     * Resolve the feature flag owning model class for a given key.
     *
     * @param string $key
     * @return string|null
     */
    public function resolveFeatureFlagOwningModel(string $key): ?string
    {
        return Arr::get($this->owningModels, Str::singular($key));
    }

    /**
     * Set the feature owning model for visibility in the dashboard.
     *
     * @param string $key
     * @param string $modelClass
     * @return self
     */
    public function setOwningModel(string $key, string $modelClass)
    {
        $this->owningModels[Str::lower(Str::singular($key))] = $modelClass;

        return $this;
    }
}
