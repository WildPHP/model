<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Traits;

use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Deserializers\GlobalDeserializerRegistry;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Factories\DeserializerFactoryProducer;
use NanoSector\Models\Helpers\DeserializerHelper;

trait DeserializableProperties
{
    /**
     * List of deserializers to use for the specified keys.
     *
     * @var array<string, DeserializerInterface|string|string[]>
     */
    protected $deserializers = [];

    /**
     * Infer deserializers from settable types.
     *
     * @param array<string, \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface> $typeDefinitions
     */
    protected function inferDeserializers(array $typeDefinitions): void
    {
        foreach ($typeDefinitions as $key => $wantedType) {
            // Assume the user knows best and skip if they have overridden this deserializer
            if (array_key_exists($key, $this->deserializers)) {
                continue;
            }

            try {
                // First, attempt to create a deserializer from the user's own type definition.
                $deserializer = DeserializerFactoryProducer::fromTypeDefinition(
                    $wantedType
                )->getDeserializer();
                $this->deserializers[$key] = $deserializer;
            } catch (DeserializationInitializationException $e) {
                // If this fails, try the global registry.
                if (($global = GlobalDeserializerRegistry::getForTypeDefinition($wantedType)) !== null) {
                    $this->deserializers[$key] = $global;
                }
                continue;
            }
        }
    }

    /**
     * @param string $key
     *
     * @return DeserializerInterface|null
     */
    private function getDeserializer(string $key): ?DeserializerInterface
    {
        if (!property_exists($this, 'deserializers') ||
            !array_key_exists($key, $this->deserializers)
        ) {
            return null;
        }

        $deserializer = DeserializerHelper::getOrNew($this->deserializers[$key]);

        if ($deserializer !== null && $deserializer !== $this->deserializers[$key]) {
            $this->deserializers[$key] = $deserializer;
        }

        return $deserializer;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    private function canDeserialize(string $name, $value): bool
    {
        $deserializer = $this->getDeserializer($name);

        return $deserializer !== null && $deserializer->canDeserialize($value);
    }

    /**
     * Deserializes a given key/value pair.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    private function deserialize(string $name, $value)
    {
        $deserializer = $this->getDeserializer($name);

        if ($deserializer === null) {
            return $value;
        }

        return $deserializer->deserialize($value);
    }
}
