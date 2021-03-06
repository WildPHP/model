<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Helpers;

use ReflectionClass;
use ReflectionException;
use WildPHP\Models\Deserializers\DeserializerInterface;
use WildPHP\Models\Model;

class ReflectionHelper
{
    /**
     * Determine whether a given class is a deserializer.
     *
     * @param string $class
     *
     * @return bool
     */
    public static function isDeserializer(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        try {
            $reflection = new ReflectionClass($class);
            return $reflection->implementsInterface(
                DeserializerInterface::class
            );
        } catch (ReflectionException $e) {
            return false;
        }
    }

    /**
     * Determine whether a given class is a model.
     *
     * @param string $class
     *
     * @return bool
     */
    public static function isModel(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        try {
            $reflection = new ReflectionClass($class);
            return $reflection->isSubclassOf(Model::class);
        } catch (ReflectionException $e) {
            return false;
        }
    }

    /**
     * Determines whether the given class has any hard dependencies required to construct it.
     *
     * @param class-string $class
     *
     * @return bool
     * @throws \ReflectionException
     */
    public static function hasDependencies(string $class): bool
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        return $constructor === null || $constructor->getNumberOfRequiredParameters() > 0;
    }
}
