<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Helpers;

use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Exceptions\TypeDefinitionException;
use NanoSector\Models\Factories\DeserializerFactoryProducer;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterpreter;

class DeserializerHelper
{
    /**
     * @param DeserializerInterface|string|string[] $wanted
     *
     * @return \NanoSector\Models\Deserializers\DeserializerInterface|null
     */
    public static function getOrNew($wanted): ?DeserializerInterface
    {
        if ($wanted === null || $wanted instanceof DeserializerInterface) {
            return $wanted;
        }

        try {
            $typeDefinition = TypeDefinitionInterpreter::interpret($wanted);

            return DeserializerFactoryProducer::fromTypeDefinition(
                $typeDefinition
            )->getDeserializer();
        } catch (DeserializationInitializationException | TypeDefinitionException $e) {
            return null;
        }
    }
}
