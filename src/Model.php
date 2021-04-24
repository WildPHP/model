<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models;

use NanoSector\Models\Exceptions\ModelException;
use NanoSector\Models\Traits\HasMagicProperties;
use NanoSector\Models\Traits\HasMandatoryProperties;
use NanoSector\Models\Traits\Hydratable;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterpreter;

abstract class Model
{
    use HasMandatoryProperties;
    use HasMagicProperties {
        __set as setProperty;
    }
    use Hydratable;

    /**
     * Whether to ignore unknown keys. False will throw an error.
     *
     * @var bool
     */
    protected $throwOnUnknown = false;

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
     * Inferred type definitions for the settable properties.
     *
     * @var array<string, \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface>
     */
    protected $typeDefinitionMap;

    /**
     * Model constructor.
     *
     * @param array<string, mixed> $properties default properties to set
     *
     * @throws \NanoSector\Models\Exceptions\ModelException|\NanoSector\Models\Exceptions\TypeDefinitionException
     */
    public function __construct(array $properties = [])
    {
        if (!$this->satisfiesMandatoryProperties($properties)) {
            throw new ModelException(
                'Model is missing one or more mandatory properties'
            );
        }

        $this->typeDefinitionMap = TypeDefinitionInterpreter::createDefinitionMap(
            $this->settable
        );

        $this->hydrate($properties);
        $this->addDefaults();
    }

    /**
     * Checks whether the given value can be set on the given key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    protected function canAssignValue(string $key, $value): bool
    {
        if (!array_key_exists($key, $this->settable)) {
            return false;
        }

        $wantedTypeDefinition = $this->typeDefinitionMap[$key];

        return $wantedTypeDefinition->validate($value);
    }

    /**
     * Adds default values to the current model.
     */
    protected function addDefaults(): void
    {
        foreach ($this->typeDefinitionMap as $key => $definition) {
            if (array_key_exists($key, $this->properties)) {
                continue;
            }

            $this->{$key} = $definition->default();
        }
    }

    /**
     * Set a property on this model, taking types into account.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws \NanoSector\Models\Exceptions\ModelException
     */
    public function __set(string $key, $value): void
    {
        if (!$this->isPropertyKnown($key)) {
            if ($this->throwOnUnknown) {
                throw new ModelException('Cannot set property with key ' . $key);
            }

            return;
        }

        if (!$this->canAssignValue($key, $value)) {
            throw new ModelException(
                'Trying to set an invalid value for key ' . $key
            );
        }

        $this->setProperty($key, $value);
    }

    /**
     * Checks whether the given property should exist on this model.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isPropertyKnown(string $key): bool
    {
        return in_array($key, $this->settable, true)
            || array_key_exists(
                $key,
                $this->settable
            );
    }
}
