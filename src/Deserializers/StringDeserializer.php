<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

/**
 * Class StringDeserializer
 *
 * @package NanoSector\Models\Deserializers
 */
class StringDeserializer implements DeserializerInterface
{
    /**
     * @inheritDoc
     */
    public function deserialize($value)
    {
        return (string)$value;
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return is_scalar($value);
    }
}
