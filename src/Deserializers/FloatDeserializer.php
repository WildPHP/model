<?php

namespace NanoSector\Models\Deserializers;

class FloatDeserializer implements DeserializerInterface
{
    /**
     * @param string $value
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
