<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Helpers;

use WildPHP\Models\Deserializers\DeserializerInterface;
use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Factories\DeserializerFactoryProducer;
use WildPHP\TypeDefinitions\Exceptions\TypeDefinitionException;
use WildPHP\TypeDefinitions\TypeDefinitionInterpreter;

class DeserializerHelper
{
    /**
     * @param DeserializerInterface|string|string[] $wanted
     *
     * @return \WildPHP\Models\Deserializers\DeserializerInterface|null
     */
    public static function getOrNew($wanted): ?DeserializerInterface
    {
        if ($wanted instanceof DeserializerInterface) {
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
