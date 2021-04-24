<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Helpers\ReflectionHelper;
use NanoSector\Models\TypeDefinitions\ArrayTypeDefinition;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterface;

class DeserializerFactoryProducer
{

    /**
     * Creates a deserializer from a given
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

        if (ReflectionHelper::isModel($typeDefinition->toDefinition())) {
            return new ModelDeserializerFactory(
                $typeDefinition->toDefinition()
            );
        }

        return new DeserializerFactory($typeDefinition->toDefinition());
    }

}
