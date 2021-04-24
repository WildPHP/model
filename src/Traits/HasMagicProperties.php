<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Traits;

use NanoSector\Models\Exceptions\ModelException;

trait HasMagicProperties
{

    /**
     * The properties in this object. You can override this in your model
     * class to set default properties.
     *
     * @var array<string, mixed>
     */
    protected $properties = [];

    /**
     * Tries to get a given property from this object.
     * Returns null on failure.
     *
     * @param string $name
     *
     * @return mixed|null
     * @throws \NanoSector\Models\Exceptions\ModelException when accessing an
     *   unknown property.
     */
    public function &__get(string $name)
    {
        if (!$this->propertyExists($name)) {
            throw new ModelException(
                'Property with key ' . $name . ' not found on this model instance'
            );
        }

        return $this->properties[$name];
    }

    /**
     * Set a property on this object.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set(string $key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * Checks whether a given property currently exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function propertyExists(string $key): bool
    {
        return array_key_exists($key, $this->properties);
    }

    /**
     * Checks whether a property is set on this object.
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name)
    {
        return $this->propertyExists($name);
    }

    /**
     * Returns the properties in this object as an
     * associative array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->properties;
    }
}
