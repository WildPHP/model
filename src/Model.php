<?php

declare(strict_types=1);

namespace NanoSector\Models;

use NanoSector\Models\Exceptions\ModelException;
use NanoSector\Models\Traits\DeserializableProperties;

abstract class Model
{
    use DeserializableProperties;

    /**
     * Whether to ignore unknown keys. False will throw an error.
     * @var bool
     */
    protected $ignoreUnknownKeys = true;

    /**
     * The properties in this object.
     *
     * @var array<string, mixed>
     */
    protected $properties = [];

    /**
     * Properties which can be assigned.
     *
     * @see gettype()
     * @var array<string, string|string[]>
     */
    protected $settable = [];

    /**
     * Properties which can be mass assigned.
     *
     * @var string[]
     */
    protected $fillable = [];

    /**
     * Properties which may not be mass assigned.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * A list of mandatory properties when first creating a model.
     * Any attempt to create a model which misses a mandatory property
     * will throw an InvalidArgumentException.
     *
     * Please note that adding properties with default values here
     * negates the purpose of making them mandatory.
     *
     * @var string[]
     */
    protected $mandatory = [];

    /**
     * A list of default values to be set when the object is initialised.
     * This list will not overwrite custom values set.
     * Any default values will still be validated.
     *
     * Please note that setting default values for mandatory
     * properties negates the purpose.
     *
     * @var array<string, mixed>
     */
    protected $defaults = [];

    /**
     * Model constructor.
     *
     * @param  array<string, mixed>  $properties default properties to set
     *
     * @throws \NanoSector\Models\Exceptions\ModelException
     */
    final public function __construct(array $properties = [])
    {
        if (empty($properties)) {
            return;
        }

        if (!$this->hasMandatoryProperties($properties)) {
            throw new ModelException('Model is missing one or more mandatory properties');
        }

        $this->inferDeserializers($this->settable);
        $this->stripInvalidProperties($properties);
        $this->generateDefaults();
        $this->addDefaults($properties);

        $this->hydrate($properties);
    }

    /**
     * Return many instances of this model from the given array.
     *
     * @param  array  $array
     *
     * @return static[]
     * @throws \NanoSector\Models\Exceptions\ModelException
     */
    public static function many(array $array): array
    {
        return array_map(
            static function (array $serializedModel) {
                return new static($serializedModel);
            },
            $array
        );
    }

    /**
     * Strips properties from the given defaults which
     * are not mass assignable or otherwise invalid.
     *
     * @param array $array
     */
    public function stripInvalidProperties(array &$array): void
    {
        foreach ($array as $key => $value) {
            if (!$this->canHydrate($key) || !$this->canAssignValue($key, $value)) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Checks whether the given key can be mass assigned.
     *
     * @param string $key
     * @return bool
     */
    public function canHydrate(string $key): bool
    {
        return empty($this->fillable) ? !in_array($key, $this->guarded) : in_array($key, $this->fillable);
    }

    /**
     * Checks whether the given value can be set on the given key.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    protected function canAssignValue(string $key, $value): bool
    {
        if (!array_key_exists($key, $this->settable)) {
            return false;
        }

        $wantedType = $this->settable[$key];

        if (is_array($wantedType)) {
            $keyType = count($wantedType) >= 2 ? $wantedType[0] : null;
            $valueType = count($wantedType) === 1 ? $wantedType[0] : $wantedType[1];
            return $this->canDeserialize($key, $value, $wantedType) || Validation::isArrayOfType(
                $value,
                $valueType,
                $keyType
            );
        }

        return $this->canDeserialize($key, $value, $wantedType) || Validation::isOfType($value, $wantedType);
    }

    /**
     * Generates default values for properties of known types.
     */
    protected function generateDefaults(): void
    {
        foreach ($this->settable as $key => $type) {
            if (is_numeric($key) || array_key_exists($key, $this->defaults)) {
                continue;
            }

            if (is_array($type)) {
                $type = 'array';
            }

            $default = Validation::defaultTypeValue($type);

            if ($default !== null) {
                $this->defaults[$key] = $default;
            }
        }
    }

    /**
     * Adds default values to an array.
     *
     * @param array $array
     */
    protected function addDefaults(array &$array): void
    {
        foreach ($this->defaults as $key => $value) {
            if (array_key_exists($key, $array)) {
                continue;
            }

            if (is_string($value) && class_exists($value)) {
                $value = new $value();
            }

            $array[$key] = $value;
        }
    }

    /**
     * Checks whether all mandatory properties exist
     * in the given array.
     *
     * @param array $array
     * @return bool
     */
    public function hasMandatoryProperties(array $array): bool
    {
        foreach ($this->mandatory as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mass assigns this model instance with the given properties.
     *
     * @param array<string, mixed> $properties
     */
    public function hydrate(array $properties): void
    {
        $this->stripInvalidProperties($properties);

        foreach ($properties as $key => $value) {
            if (!$this->canHydrate($key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * Tries to get a given property from this object.
     * Returns null on failure.
     *
     * @param  string  $name
     *
     * @return mixed|null
     * @throws \NanoSector\Models\Exceptions\ModelException when accessing an unknown property.
     */
    public function &__get(string $name)
    {
        if (!$this->propertyExists($name)) {
            throw new ModelException('Property with key ' . $name . ' not found on this model instance');
        }

        return $this->properties[$name];
    }

    /**
     * Tries to set a given property on this object.
     *
     * @param  string  $name
     * @param  mixed  $value
     *
     * @throws \NanoSector\Models\Exceptions\ModelException|\ReflectionException
     */
    public function __set(string $name, $value)
    {
        if (!$this->isPropertySettable($name)) {
            if (!$this->ignoreUnknownKeys) {
                throw new ModelException('Cannot set property with key ' . $name);
            }

            return;
        }

        $wantedType = $this->settable[$name];
        if ($this->canDeserialize($name, $value, $wantedType)) {
            $value = $this->deserialize($name, $value, $wantedType);
        }

        if (!$this->canAssignValue($name, $value)) {
            throw new ModelException('Trying to set an invalid value.');
        }

        $this->properties[$name] = $value;
    }

    /**
     * Checks whether a given property currently exists.
     *
     * @param string $key
     * @return bool
     */
    public function propertyExists(string $key): bool
    {
        return array_key_exists($key, $this->properties) && $this->properties[$key] !== null;
    }

    /**
     * Checks whether the given property should exist on this model.
     *
     * @param string $key
     * @return bool
     */
    public function isPropertySettable(string $key): bool
    {
        return in_array($key, $this->settable, true) || array_key_exists($key, $this->settable);
    }

    /**
     * Checks whether a property is set on this object.
     *
     * @param string $name
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
     * @return array
     */
    public function toArray(): array
    {
        return $this->properties;
    }
}
