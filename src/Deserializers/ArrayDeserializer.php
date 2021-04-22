<?php

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationException;

class ArrayDeserializer implements DeserializerInterface
{
    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * ArrayDeserializer constructor.
     * @param DeserializerInterface $deserializer
     */
    public function __construct(DeserializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * @throws \NanoSector\Models\Exceptions\DeserializationException
     */
    public function deserialize($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        foreach ($value as $key => $val) {
            if (!$this->deserializer->canDeserialize($val)) {
                throw new DeserializationException('Cannot deserialize one or more items in the given array.');
            }

            $value[$key] = $this->deserializer->deserialize($val);
        }

        return $value;
    }

    public function canDeserialize($value): bool
    {
        return is_array($value);
    }
}
