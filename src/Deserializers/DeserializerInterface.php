<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\Deserializers;

interface DeserializerInterface
{
    public function deserialize($value);

    public function canDeserialize($value): bool;
}
