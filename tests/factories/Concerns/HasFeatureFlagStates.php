<?php

namespace Featica\Tests\Factories\Concerns;

use Featica\Featica;
use Featica\Feature;
use Illuminate\Support\Arr;

trait HasFeatureFlagStates
{
    /**
     * Indicate the enabled features.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function enabledFeatures($features)
    {
        $enabledFeatures = array_fill_keys(Arr::wrap($features), Feature::STATE_ON);

        return $this->state(function (array $attributes) use ($enabledFeatures) {
            return [
                'feature_flags' => array_merge(
                    Arr::wrap(Arr::get($attributes, 'feature_flags')),
                    $enabledFeatures
                ),
            ];
        });
    }

    /**
     * Indicate the disabled features.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function disabledFeatures($features)
    {
        $disabledFeatures = array_fill_keys(Arr::wrap($features), Feature::STATE_OFF);

        return $this->state(function (array $attributes) use ($disabledFeatures) {
            return [
                'feature_flags' => array_merge(
                    Arr::wrap(Arr::get($attributes, 'feature_flags')),
                    $disabledFeatures
                ),
            ];
        });
    }
}
