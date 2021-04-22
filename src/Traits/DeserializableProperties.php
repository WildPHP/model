<?php

namespace NanoSector\Models\Traits;

use DateTime;
use NanoSector\Models\Deserializers\DateTimeDeserializer;
use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Deserializers\FloatDeserializer;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Factories\DeserializerFactoryProducer;
use NanoSector\Models\Helpers\DeserializerHelper;

trait DeserializableProperties
{

    /**
     * List of types to automatically deserialize.
     *
     * @var array<string, string|DeserializerInterface>
     */
    protected $typeDeserializers = [
      'double'        => FloatDeserializer::class,
      DateTime::class => DateTimeDeserializer::class,
    ];

    /**
     * List of deserializers to use for the specified keys.
     *
     * @var array<string, DeserializerInterface|string|string[]>
     */
    protected $deserializers = [];

    /**
     * Infer deserializers from the given wanted type array
     *
     * @param  array<string, string|string[]>  $wantedTypes
     */
    protected function inferDeserializers(array $wantedTypes): void
    {
        foreach ($wantedTypes as $key => $wantedType) {
            // Assume the user knows best and skip if they have overridden this deserializer
            if (array_key_exists($key, $this->deserializers)) {
                continue;
            }

            try {
                $deserializer = DeserializerFactoryProducer::fromTypeDefinition(
                  $wantedType
                )->getDeserializer();
                $this->deserializers[$key] = $deserializer;
            } catch (DeserializationInitializationException $e) {
                continue;
            }
        }
    }

    /**
     * @param  array|string  $type
     *
     * @return DeserializerInterface|null
     */
    private function getTypeDeserializer($type): ?DeserializerInterface
    {
        if (!is_string($type) || !array_key_exists(
            $type,
            $this->typeDeserializers
          )) {
            return null;
        }

        $deserializer = DeserializerHelper::getOrNew($this->typeDeserializers[$type]);

        if ($deserializer !== $this->typeDeserializers[$type]) {
            $this->typeDeserializers[$type] = $deserializer;
        }

        return $deserializer;
    }

    /**
     * @param  string  $key
     *
     * @return DeserializerInterface|null
     */
    private function getDeserializer(string $key): ?DeserializerInterface
    {
        if (
          !property_exists($this, 'deserializers') ||
          !array_key_exists($key, $this->deserializers)
        ) {
            return null;
        }

        $deserializer = DeserializerHelper::getOrNew($this->deserializers[$key]);

        if ($deserializer !== $this->deserializers[$key]) {
            $this->deserializers[$key] = $deserializer;
        }

        return $deserializer;
    }

    /**
     * @param  string  $name
     * @param  mixed  $value
     * @param  string|array  $wantedType
     *
     * @return bool
     */
    private function canDeserialize(string $name, $value, $wantedType): bool
    {
        $deserializer = $this->getDeserializer($name)
                        ?? $this->getTypeDeserializer($wantedType);

        return $deserializer !== null && $deserializer->canDeserialize($value);
    }

    /**
     * Deserializes a given key/value pair.
     *
     * @param  string  $name
     * @param  mixed  $value
     * @param  string|array  $wantedType
     *
     * @return mixed
     */
    private function deserialize(string $name, $value, $wantedType)
    {
        $deserializer = $this->getDeserializer($name)
                        ?? $this->getTypeDeserializer($wantedType);

        if ($deserializer === null) {
            return $value;
        }

        return $deserializer->deserialize($value);
    }

}
