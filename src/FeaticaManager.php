<?php

namespace Featica;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Throwable;

class FeaticaManager
{
    use FeaticaManagerDashboard;

    /**
     * The features that exist within the application.
     *
     * @var array
     */
    protected array $features = [];

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
     * Decide what to return for a given model & feature.
     *
     * @param string $feature The feature
     * @param \Illuminate\Database\Eloquent\Model|null $for
     * @param Closure $on The enabled
     * @param Closure $disabled The disabled
     * @return mixed
     */
    public function when(string $feature, ?Model $for = null, mixed $on = null, mixed $off = null)
    {
        if (empty($for)) {
            return $this->evaluate($this->isEnabled($feature), $on, $off);
        }

        return $this->evaluate($this->modelHasFeature($for, $feature), $on, $off);
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
}
