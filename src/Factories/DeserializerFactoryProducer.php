<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Factories;

use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Helpers\ReflectionHelper;
use WildPHP\Models\TypeDefinitions\ArrayTypeDefinition;
use WildPHP\Models\TypeDefinitions\TypeDefinitionInterface;

class DeserializerFactoryProducer
{

    /**
     * Creates a deserializer from a given type definition.
     *
     * @param \WildPHP\Models\TypeDefinitions\TypeDefinitionInterface $typeDefinition
     *
     * @return \WildPHP\Models\Factories\DeserializerFactoryInterface
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
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
