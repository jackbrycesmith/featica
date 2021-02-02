<?php

namespace Featica;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Throwable;

class Featica
{
    // Types
    const TYPE_USER = 'user';
    const TYPE_TEAM = 'team';
    const TYPE_SYSTEM = 'system'; // TODO: choose either 'system' or 'global'
    const TYPE_GLOBAL = 'global'; // TODO: choose either 'system' or 'global'

    /**
     * The features that exist within the application.
     *
     * @var array
     */
    public static array $features = [];

    /**
     * The feature owning models that exist within the application.
     *
     * @var array
     */
    public static array $owningModels = [
        'user' => 'App\\Models\\User',
        'team' => 'App\\Models\\Team',
    ];

    public static array $owningModelDashboardSearch = [
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
    public static array $dashboardModelsDefaultSearchColumns = ['id', 'uuid', 'name', 'email'];

    /**
     * The callback that should be used to authenticate Featica dashboard users.
     *
     * @var ?Closure
     */
    public static ?Closure $authUsing;

    /**
     * Define a feature flag.
     *
     * @param Feature $feature
     * @return \Featica\Feature
     */
    public static function add(Feature $feature)
    {
        static::$features[$feature->key] = $feature;

        return $feature;
    }

    /**
     * Searches for the first match.
     *
     * @param string $feature The feature
     *
     * @return \Featica\Feature|null
     */
    public static function find(string $feature): ?Feature
    {
        return Arr::get(static::$features, $feature);
    }

    /**
     * Check whether the given model has a given feature enabled.
     *
     * @param mixed $model The model
     * @param string $featureKey The feature key
     *
     * @return boolean true if enabled, otherwise false
     */
    public static function modelHasFeature($model, string $featureKey): bool
    {
        $feature = self::find($featureKey);
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
    public static function inertiaShareData(): array
    {
        return [
            'features' => array_map(fn($feature) => $feature->shareableData(), static::$features)
        ];
    }

    /**
     * Set the callback that should be used to authenticate Featica dashboard
     * users.
     *
     * @param \Closure $callback
     *
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Decide what to return for a given model & feature.
     *
     * @param string $feature The feature
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param Closure $on The enabled
     * @param Closure $disabled The disabled
     *
     * @return mixed
     */
    public static function when(string $feature, ?Model $model = null, mixed $on = null, mixed $off = null)
    {
        if (empty($model)) {
            return self::evaluate(self::isEnabled($feature), $on, $off);
        }

        return self::evaluate(self::modelHasFeature($model, $feature), $on, $off);
    }

    /**
     * Return whenTrue or whenFalse for a given match.
     *
     * @param boolean $match The match
     * @param mixed $whenTrue The value to return when true
     * @param mixed $whenFalse The value to return when false
     *
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
     *
     * @return boolean True if the specified feature key is enabled, False otherwise.
     */
    public static function isEnabled(string $featureKey): bool
    {
        $feature = self::find($featureKey);

        if (empty($feature)) {
            return false;
        }

        return $feature->isEnabled();
    }

    /**
     * Determine if the given request can access the Featica dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    public static function resolveFeatureFlagOwningModel($key)
    {
        return Arr::get(static::$owningModels, Str::singular($key));
    }

    public static function setOwningModel(string $key, $modelClass)
    {
        static::$owningModels[Str::lower(Str::singular($key))] = $modelClass;

        return new static;
    }
}
