<?php

namespace NanoSector\Models\Helpers;

use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Factories\DeserializerFactoryProducer;
use NanoSector\Models\Model;
use ReflectionClass;
use ReflectionException;

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
            return DeserializerFactoryProducer::fromTypeDefinition(
              $wanted
            )->getDeserializer();
        } catch (DeserializationInitializationException $e) {
            return null;
        }
    }

    /**
     * Determine whether a given class is a deserializer.
     *
     * @param  string  $class
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
     * @param  string  $class
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

}
