<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Factories;

use ReflectionException;
use WildPHP\Models\Deserializers\DeserializerInterface;
use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Helpers\ReflectionHelper;

class DeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var string
     */
    private $className;

    /**
     * @param class-string $className
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $className)
    {
        if (!ReflectionHelper::isDeserializer($className)) {
            throw new DeserializationInitializationException(
                'Given class is not a deserializer.'
            );
        }

        try {
            if (!ReflectionHelper::hasDependencies($className)) {
                throw new DeserializationInitializationException(
                    'The given deserializer class has dependencies which cannot be satisfied. ' .
                    'Please instantiate this object yourself.'
                );
            }
        } catch (ReflectionException $e) {
            throw new DeserializationInitializationException(
                'Reflection of the given class failed; refusing to instantiate it.',
                0,
                $e
            );
        }

        $this->className = $className;
    }

    public function getDeserializer(): DeserializerInterface
    {
        return new $this->className();
    }
}
