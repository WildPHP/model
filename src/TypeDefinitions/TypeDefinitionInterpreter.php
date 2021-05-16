<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\TypeDefinitions;

class TypeDefinitionInterpreter
{

    /**
     * Generates a definition map from a given
     *
     * @param array<string, string|string[]> $array
     *
     * @return array<string, \WildPHP\Models\TypeDefinitions\TypeDefinitionInterface>
     * @throws \WildPHP\Models\Exceptions\TypeDefinitionException
     */
    public static function createDefinitionMap(array $array): array
    {
        $mappedDefinitions = [];
        foreach ($array as $key => $item) {
            $mappedDefinitions[$key] = self::interpret($item);
        }

        return $mappedDefinitions;
    }

    /**
     * Interpret a given array.
     *
     * @param string|string[] $definition
     *
     * @throws \WildPHP\Models\Exceptions\TypeDefinitionException
     */
    public static function interpret($definition): TypeDefinitionInterface
    {
        if (is_array($definition)) {
            return new ArrayTypeDefinition(self::interpret($definition[0]));
        }

        if (class_exists($definition)) {
            return new ClassTypeDefinition($definition);
        }

        return new PrimitiveTypeDefinition($definition);
    }
}
