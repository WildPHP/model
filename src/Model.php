<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models;

use WildPHP\Models\Exceptions\ModelException;
use WildPHP\Models\Traits\HasMagicProperties;
use WildPHP\Models\Traits\HasMandatoryProperties;
use WildPHP\Models\Traits\Hydratable;
use WildPHP\TypeDefinitions\TypeDefinitionInterpreter;

abstract class Model
{
    use HasMandatoryProperties;
    use HasMagicProperties {
        __set as setProperty;
    }
    use Hydratable;

    /**
     * Properties which can be assigned in the type definition format.
     * This defines your model structure. For example:
     *
     * $structure = [
     *     'firstKey'  => 'string',           // Simple string value.
     *     'secondKey' => SomeClass::class,   // Instance of SomeClass.
     * ];
     *
     * Properties can also be set in an array format by passing the desired type
     * as its first element. For example:
     *
     * $structure = [
     *     'firstKey'  => ['string'],         // Array of strings.
     *     'secondKey' => [SomeClass::class], // Array of SomeClass instances
     * ];
     *
     * The above examples may be mixed to create complex models.
     *
     * @var array<string, string|string[]>
     * @see \WildPHP\Models\Model::getStructure()
     */
    protected $structure = [];

    /**
     * Inferred type definitions for the settable properties.
     *
     * @var array<string, \WildPHP\TypeDefinitions\TypeDefinitionInterface>
     */
    protected $typeDefinitionMap;

    /**
     * Model constructor.
     *
     * @param array<string, mixed> $properties default properties to set
     *
     * @throws \WildPHP\Models\Exceptions\ModelException|\WildPHP\TypeDefinitions\Exceptions\TypeDefinitionException
     */
    public function __construct(array $properties = [])
    {
        if (!$this->satisfiesMandatoryProperties($properties)) {
            throw new ModelException(
                'Model was initialized without one or more mandatory parameters'
            );
        }

        $this->typeDefinitionMap = TypeDefinitionInterpreter::createDefinitionMap(
            $this->getStructure()
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
    public function canAssignValue(string $key, $value): bool
    {
        if (!$this->isPropertyKnown($key)) {
            return false;
        }

        $wantedTypeDefinition = $this->typeDefinitionMap[$key];

        return $wantedTypeDefinition->validate($value);
    }

    /**
     * Adds default values to the current model.
     */
    public function addDefaults(): void
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
     * @param mixed  $value
     *
     * @throws \WildPHP\Models\Exceptions\ModelException
     */
    public function __set(string $key, $value): void
    {
        if (!$this->isPropertyKnown($key)) {
            throw new ModelException('Cannot set property with key ' . $key);
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
        return array_key_exists(
            $key,
            $this->getStructure()
        );
    }

    /**
     * Returns this model's defined structure.
     *
     * @return array<string, string|string[]>
     * @see \WildPHP\Models\Model::$structure
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * Returns the generated type definition map for this model.
     *
     * @return array<string, \WildPHP\TypeDefinitions\TypeDefinitionInterface>
     */
    public function getTypeDefinitionMap(): array
    {
        return $this->typeDefinitionMap;
    }
}
