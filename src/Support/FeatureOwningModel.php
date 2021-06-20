<?php

namespace Featica\Support;

class FeatureOwningModel
{
    /**
     * Create a new instance.
     *
     * @param string $className
     * @param ?string $icon
     */
    public function __construct(
        public string $className,
        public ?string $icon = null
    ){}
}
