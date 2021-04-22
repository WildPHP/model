<?php

namespace NanoSector\Models\Deserializers;

interface DeserializerInterface
{
    public function deserialize($value);

    public function canDeserialize($value): bool;
}
