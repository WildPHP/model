<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models;

use WildPHP\Models\Deserializers\TypeDefinitionMapAdapter;
use WildPHP\Models\Traits\DeserializableProperties;

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

        $this->deserializers = TypeDefinitionMapAdapter::inferDeserializers($this->getTypeDefinitionMap());
        $this->hydrate($properties);
    }

    /**
     * @inheritDoc
     */
    public function canAssignValue(string $key, $value): bool
    {
        return parent::canAssignValue($key, $value) || $this->canDeserialize($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $key, $value): void
    {
        if ($this->canDeserialize($key, $value)) {
            $value = $this->deserialize($key, $value);
        }

        parent::__set($key, $value);
    }
}
