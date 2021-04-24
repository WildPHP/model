<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\ReflectionHelper;
use NanoSector\Models\TypeDefinitions\ArrayTypeDefinition;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterface;

class DeserializerFactoryProducer
{

    /**
     * Creates a deserializer from a given type definition.
     *
     * @param \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface $typeDefinition
     *
     * @return \NanoSector\Models\Factories\DeserializerFactoryInterface
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public static function fromTypeDefinition(
        TypeDefinitionInterface $typeDefinition
    ): DeserializerFactoryInterface {
        if ($typeDefinition instanceof ArrayTypeDefinition) {
            return new ArrayDeserializerFactory(
                self::fromTypeDefinition($typeDefinition->getContentDefinition())
            );
        }

        $definition = $typeDefinition->toDefinition();

        if (is_array($definition)) {
            throw new DeserializationInitializationException(
                'Got array definition from an object whose type is not ArrayTypeDefinition. Please file a bug.'
            );
        }

        if (ReflectionHelper::isModel($definition)) {
            return new ModelDeserializerFactory(
                $definition
            );
        }

        if (class_exists($definition)) {
            return new DeserializerFactory($definition);
        }

        // We're out of options, just bail. This can happen when a primitive type is passed.
        throw new DeserializationInitializationException(
            'No appropriate factory object could be created.'
        );
    }
}
