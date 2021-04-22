<?php

namespace NanoSector\Models\Factories;

use NanoSector\Models\Helpers\DeserializerHelper;

class DeserializerFactoryProducer
{

    /**
     * Creates a deserializer from a given
     *
     * @param string|string[] $definition
     *
     * @return \NanoSector\Models\Factories\DeserializerFactoryInterface
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public static function fromTypeDefinition($definition): DeserializerFactoryInterface
    {
        if (is_array($definition)) {
            return new ArrayDeserializerFactory(
              new DeserializerFactory($definition[0])
            );
        }

        if (DeserializerHelper::isModel($definition)) {
            return new ModelDeserializerFactory($definition);
        }

        return new DeserializerFactory($definition);
    }
}
