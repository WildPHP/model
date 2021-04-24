<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use DateTime;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Factories\DeserializerFactory;
use NanoSector\Models\Helpers\ReflectionHelper;
use NanoSector\Models\TypeDefinitions\TypeDefinitionInterface;

class GlobalDeserializerRegistry
{
    /**
     * List of types to automatically deserialize.
     *
     * @var array<string, string|DeserializerInterface>
     */
    protected static $typeDeserializers = [];

    /**
     * Generate and add deserializers for date and time types.
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
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
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public static function juggle(): void
    {
        try {
            foreach (JuggleDeserializer::JUGGLE_TYPES as $type) {
                self::add($type, new JuggleDeserializer($type));
            }
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
     * @param string                                                        $type
     * @param string|\NanoSector\Models\Deserializers\DeserializerInterface $deserializer
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public static function add(string $type, $deserializer): void
    {
        if ($deserializer instanceof DeserializerInterface) {
            self::$typeDeserializers[$type] = $deserializer;
            return;
        }

        if (!is_string($deserializer)
            || !ReflectionHelper::isDeserializer($deserializer)) {
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
     * @return \NanoSector\Models\Deserializers\DeserializerInterface|null
     */
    public static function get(string $type): ?DeserializerInterface
    {
        return self::$typeDeserializers[$type] ?? null;
    }

    /**
     * Get a global deserializer for the given type definition.
     *
     * @param \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface $typeDefinition
     *
     * @return \NanoSector\Models\Deserializers\DeserializerInterface|null
     * @see \NanoSector\Models\Deserializers\GlobalDeserializerRegistry::get()
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
