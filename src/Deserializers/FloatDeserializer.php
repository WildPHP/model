<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

/**
 * Class FloatDeserializer
 *
 * @package NanoSector\Models\Deserializers
 */
class FloatDeserializer implements DeserializerInterface
{
    /**
     * @inheritDoc
     */
    public function deserialize($value)
    {
        return (double)$value;
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return is_numeric($value);
    }
}
