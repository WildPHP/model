<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models;

use NanoSector\Models\Traits\DeserializableProperties;

abstract class DeserializableModel extends Model
{
    use DeserializableProperties;

    /**
     * DeserializableModel constructor.
     *
     * @inheritDoc
     */
    public function __construct(array $properties = [])
    {
        // Bypass parent hydration since we can only initialise deserializers
        // after the type definitions have been created.
        parent::__construct([]);

        $this->inferDeserializers($this->typeDefinitionMap);
        $this->hydrate($properties);
    }

    /**
     * @inheritDoc
     */
    protected function canAssignValue(string $key, $value): bool
    {
        return parent::canAssignValue($key, $value) || $this->canDeserialize($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $key, $value)
    {
        if ($this->canDeserialize($key, $value)) {
            $value = $this->deserialize($key, $value);
        }

        parent::__set($key, $value);
    }
}
