<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Deserializers;

use DateTime;
use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Factories\DeserializerFactory;
use WildPHP\Models\Helpers\ReflectionHelper;
use WildPHP\TypeDefinitions\PrimitiveTypeDefinition;
use WildPHP\TypeDefinitions\TypeDefinitionInterface;

/**
 * Class GlobalDeserializerRegistry
 *
 * @package NanoSector\Models\Deserializers
 */
class GlobalDeserializerRegistry
{
    /**
     * List of types to automatically deserialize.
     *
     * @var array<string, DeserializerInterface>
     */
    protected static $typeDeserializers = [];

    /**
     * Generate and add all default deserializers.
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public static function all(): void
    {
        self::dateTime();
        self::juggle();
    }

    /**
     * Generate and add deserializers for date and time types.
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public static function dateTime(): void
    {
        try {
            self::add(DateTime::class, new DateTimeDeserializer());
        } catch (DeserializationInitializationException $e) {
            throw new DeserializationInitializationException(
                'Could not initiate built-in deserializers. Please file a bug!',
                0,
                $e
            );
        }
    }

    /**
     * Generate and add deserializers based on type juggling.
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public static function juggle(): void
    {
        try {
            self::add(PrimitiveTypeDefinition::BOOLEAN, new BooleanDeserializer());
            self::add(PrimitiveTypeDefinition::FLOAT, new FloatDeserializer());
            self::add(PrimitiveTypeDefinition::INTEGER, new IntegerDeserializer());
            self::add(PrimitiveTypeDefinition::STRING, new StringDeserializer());
        } catch (DeserializationInitializationException $e) {
            throw new DeserializationInitializationException(
                'Could not initiate built-in deserializers. Please file a bug!',
                0,
                $e
            );
        }
    }

    /**
     * Add a new global deserializer.
     *
     * @param string                                                           $type
     * @param class-string|\WildPHP\Models\Deserializers\DeserializerInterface $deserializer
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public static function add(string $type, $deserializer): void
    {
        if ($deserializer instanceof DeserializerInterface) {
            self::$typeDeserializers[$type] = $deserializer;
            return;
        }

        if (
            !is_string($deserializer)
            || !ReflectionHelper::isDeserializer($deserializer)
        ) {
            throw new DeserializationInitializationException('Given class is not a deserializer');
        }

        $factory = new DeserializerFactory($deserializer);
        self::$typeDeserializers[$type] = $factory->getDeserializer();
    }

    /**
     * Get a global deserializer for the given type, or null if none exists.
     *
     * @param string $type
     *
     * @return \WildPHP\Models\Deserializers\DeserializerInterface|null
     */
    public static function get(string $type): ?DeserializerInterface
    {
        return self::$typeDeserializers[$type] ?? null;
    }

    /**
     * Get a global deserializer for the given type definition.
     *
     * @param \WildPHP\TypeDefinitions\TypeDefinitionInterface $typeDefinition
     *
     * @return \WildPHP\Models\Deserializers\DeserializerInterface|null
     * @see \WildPHP\Models\Deserializers\GlobalDeserializerRegistry::get()
     */
    public static function getForTypeDefinition(TypeDefinitionInterface $typeDefinition): ?DeserializerInterface
    {
        $definition = $typeDefinition->toDefinition();

        if (!is_string($definition)) {
            return null;
        }

        return self::get($definition);
    }
}
