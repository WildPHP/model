<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

interface DeserializerInterface
{
    public function deserialize($value);

    public function canDeserialize($value): bool;
}
