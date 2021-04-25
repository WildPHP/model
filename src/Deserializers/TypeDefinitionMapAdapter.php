<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Factories\DeserializerFactoryProducer;

class TypeDefinitionMapAdapter
{
    /**
     * Infer deserializers from settable types.
     *
     * @param array<string, \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface> $typeDefinitions
     *
     * @return array<string, \NanoSector\Models\Deserializers\DeserializerInterface>
     */
    public static function inferDeserializers(array $typeDefinitions): array
    {
        $inferred = [];
        foreach ($typeDefinitions as $key => $wantedType) {
            try {
                // First, attempt to create a deserializer from the user's own type definition.
                $deserializer = DeserializerFactoryProducer::fromTypeDefinition(
                    $wantedType
                )->getDeserializer();

                $inferred[$key] = $deserializer;
            } catch (DeserializationInitializationException $exception) {
                // If this fails, try the global registry.
                $global = GlobalDeserializerRegistry::getForTypeDefinition($wantedType);
                if ($global !== null) {
                    $inferred[$key] = $global;
                }

                continue;
            }
        }

        return $inferred;
    }
}
