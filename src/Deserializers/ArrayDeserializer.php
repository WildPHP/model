<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Deserializers;

use WildPHP\Models\Exceptions\DeserializationException;

class ArrayDeserializer implements DeserializerInterface
{
    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * ArrayDeserializer constructor.
     *
     * @param DeserializerInterface $deserializer
     */
    public function __construct(DeserializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * @param mixed $value
     *
     * @return array<string|int, mixed>
     * @throws \WildPHP\Models\Exceptions\DeserializationException
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

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->deserializer->canDeserialize($item)) {
                return false;
            }
        }

        return true;
    }
}
