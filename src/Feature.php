<?php

namespace Featica;

use JsonSerializable;

class Feature implements JsonSerializable
{
    const STATE_ON = 'on';
    const STATE_OFF = 'off';

    /**
     * Create a new feature instance.
     *
     * @param string $key
     * @param string $state
     * @param string $type
     * @param ?array $meta
     */
    public function __construct(
        public string $key,
        public string $state = Feature::STATE_ON,
        public string $type = Featica::TYPE_USER,
        public ?array $meta = null
    ){}

    /**
     * Determines if enabled.
     *
     * @return boolean True if enabled, False otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->state === Feature::STATE_ON;
    }

    /**
     * Feature data that is considered safe to share with users.
     *
     * @return array
     */
    public function shareableData(): array
    {
        return [
            'key' => $this->key,
            'type' => $this->type,
            'state' => $this->state,
        ];
    }

    /**
     * Get the JSON serializable representation of the object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'key' => $this->key,
            'type' => $this->type,
            'state' => $this->state,
            'meta' => $this->meta,
        ];
    }
}
