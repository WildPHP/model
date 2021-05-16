<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Traits;

use WildPHP\Models\Deserializers\DeserializerInterface;
use WildPHP\Models\Helpers\DeserializerHelper;

trait DeserializableProperties
{
    /**
     * List of deserializers to use for the specified keys.
     *
     * @var array<string, DeserializerInterface|string|string[]>
     */
    protected $deserializers = [];

    /**
     * @param string $key
     *
     * @return DeserializerInterface|null
     */
    public function getDeserializer(string $key): ?DeserializerInterface
    {
        if (
            !property_exists($this, 'deserializers') ||
            !array_key_exists($key, $this->deserializers)
        ) {
            return null;
        }

        $deserializer = DeserializerHelper::getOrNew($this->deserializers[$key]);

        if ($deserializer !== null && $deserializer !== $this->deserializers[$key]) {
            $this->deserializers[$key] = $deserializer;
        }

        return $deserializer;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    public function canDeserialize(string $name, $value): bool
    {
        $deserializer = $this->getDeserializer($name);

        return $deserializer !== null && $deserializer->canDeserialize($value);
    }

    /**
     * Deserializes a given key/value pair.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    public function deserialize(string $name, $value)
    {
        $deserializer = $this->getDeserializer($name);

        if ($deserializer === null) {
            return $value;
        }

        return $deserializer->deserialize($value);
    }
}
