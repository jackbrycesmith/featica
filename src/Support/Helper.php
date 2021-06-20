<?php

namespace Featica\Support;

use Featica\HasFeatureFlags;

class Helper
{
    /**
     * Determines whether the given class uses the HasFeatureFlags trait.
     *
     * @param string $class
     * @return boolean
     */
    public static function uses_feature_flags(string $class): bool
    {
        if (! class_exists($class)) {
            return false;
        }

        return in_array(
            needle: HasFeatureFlags::class,
            haystack: array_keys(class_uses_recursive($class))
        );
    }
}
