<?php

declare(strict_types=1);

namespace NanoSector\Models;

use NanoSector\Models\Exceptions\ModelException;
use NanoSector\Models\Traits\DeserializableProperties;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterpreter;

abstract class Model
{

    use DeserializableProperties;

    /**
     * Whether to ignore unknown keys. False will throw an error.
     *
     * @var bool
     */
    protected $ignoreUnknownKeys = true;

    /**
     * The properties in this object. You can override this in your model
     * class to set default properties.
     *
     * @var array<string, mixed>
     */
    protected $properties = [];

    /**
     * Properties which can be assigned in the type definition format.
     * This defines your model. For example:
     *
     * $settable = [
     *     'firstKey'  => 'string',           // Simple string value.
     *     'secondKey' => SomeClass::class,   // Instance of SomeClass.
     * ];
     *
     * Properties can also be set in an array format by passing the desired type
     * as its first element. For example:
     *
     * $settable = [
     *     'firstKey'  => ['string'],         // Array of strings.
     *     'secondKey' => [SomeClass::class], // Array of SomeClass instances
     * ];
     *
     * The above examples may be mixed to create complex models.
     *
     * @var array<string, string|string[]>
     */
    protected $settable = [];

    /**
     * Properties which may be mass assigned or hydrated.
     * Setting this when guarded is set will override guarded.
     *
     * @var string[]
     * @see \NanoSector\Models\Model::$guarded
     */
    protected $fillable = [];

    /**
     * Properties which may not be mass assigned or hydrated.
     * Setting this when fillable is set has no effect.
     *
     * @var string[]
     * @see \NanoSector\Models\Model::$fillable
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
     * Inferred type definitions for the settable properties.
     *
     * @var array<string, \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface>
     */
    private $typeDefinitions;

    /**
     * Model constructor.
     *
     * @param  array<string, mixed>  $properties  default properties to set
     *
     * @throws \NanoSector\Models\Exceptions\ModelException|\NanoSector\Models\Exceptions\TypeDefinitionException
     */
    final public function __construct(array $properties = [])
    {
        if (!$this->hasMandatoryProperties($properties)) {
            throw new ModelException(
              'Model is missing one or more mandatory properties'
            );
        }

        $this->typeDefinitions = TypeDefinitionInterpreter::createDefinitionMap(
          $this->settable
        );

        $this->inferDeserializers();

        $this->hydrate($properties);
        $this->addDefaults();
    }

    /**
     * Checks whether all mandatory properties exist
     * in the given array.
     *
     * @param  array  $array
     *
     * @return bool
     */
    private function hasMandatoryProperties(array $array): bool
    {
        return array_intersect(
                 array_keys($array),
                 $this->mandatory
               ) === $this->mandatory;
    }

    /**
     * Mass assigns this model instance with the given properties.
     *
     * @param  array<string, mixed>  $properties
     */
    public function hydrate(array $properties): void
    {
        if (empty($properties)) {
            return;
        }

        $this->stripInvalidProperties($properties);

        foreach ($properties as $key => $value) {
            if (!$this->canHydrate($key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /**
     * Strips properties from the given defaults which
     * are not mass assignable or otherwise invalid.
     *
     * @param  array  $array
     */
    public function stripInvalidProperties(array &$array): void
    {
        foreach ($array as $key => $value) {
            if (!$this->canHydrate($key) || !$this->canAssignValue(
                $key,
                $value
              )) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Checks whether the given key can be mass assigned.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function canHydrate(string $key): bool
    {
        return empty($this->fillable) ? !in_array(
          $key,
          $this->guarded
        ) : in_array($key, $this->fillable);
    }

    /**
     * Checks whether the given value can be set on the given key.
     *
     * @param  string  $key
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function canAssignValue(string $key, $value): bool
    {
        if (!array_key_exists($key, $this->settable)) {
            return false;
        }

        $wantedType = $this->settable[$key];
        $wantedTypeDefinition = $this->typeDefinitions[$key];

        return $this->canDeserialize($key, $value, $wantedType)
               || $wantedTypeDefinition->validate($value);
    }

    /**
     * Adds default values to the current model.
     */
    protected function addDefaults(): void
    {
        foreach ($this->typeDefinitions as $key => $definition) {
            if (array_key_exists($key, $this->properties)) {
                continue;
            }

            $this->{$key} = $definition->default();
        }
    }

    /**
     * Return many instances of this model from the given array.
     *
     * @param  array  $array
     *
     * @return static[]
     * @throws \NanoSector\Models\Exceptions\ModelException|\NanoSector\Models\Exceptions\TypeDefinitionException
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
     * Returns the properties in this object as an
     * associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->properties;
    }

    /**
     * Tries to get a given property from this object.
     * Returns null on failure.
     *
     * @param  string  $name
     *
     * @return mixed|null
     * @throws \NanoSector\Models\Exceptions\ModelException when accessing an
     *   unknown property.
     */
    public function &__get(string $name)
    {
        if (!$this->propertyExists($name)) {
            throw new ModelException(
              'Property with key '.$name.' not found on this model instance'
            );
        }

        return $this->properties[$name];
    }

    /**
     * Set a property on this object.
     *
     * @param  string  $name
     * @param  mixed  $value
     *
     * @throws \NanoSector\Models\Exceptions\ModelException
     */
    public function __set(string $name, $value)
    {
        if (!$this->isPropertySettable($name)) {
            if (!$this->ignoreUnknownKeys) {
                throw new ModelException('Cannot set property with key '.$name);
            }

            return;
        }

        $wantedType = $this->settable[$name];
        if ($this->canDeserialize($name, $value, $wantedType)) {
            $value = $this->deserialize($name, $value, $wantedType);
        }

        if (!$this->canAssignValue($name, $value)) {
            throw new ModelException(
              'Trying to set an invalid value for key '.$name
            );
        }

        $this->properties[$name] = $value;
    }

    /**
     * Checks whether a given property currently exists.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function propertyExists(string $key): bool
    {
        return array_key_exists($key, $this->properties);
    }

    /**
     * Checks whether the given property should exist on this model.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isPropertySettable(string $key): bool
    {
        return in_array($key, $this->settable, true) || array_key_exists(
            $key,
            $this->settable
          );
    }

    /**
     * Checks whether a property is set on this object.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function __isset(string $name)
    {
        return $this->propertyExists($name);
    }

}
