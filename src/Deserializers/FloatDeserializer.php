<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\Deserializers;

class FloatDeserializer implements DeserializerInterface
{
    /**
     * @param string $value
     *
     * @return float
     */
    public function deserialize($value): float
    {
        return (float)$value;
    }

    public function canDeserialize($value): bool
    {
        return is_numeric($value);
    }
}
