<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Deserializers\DeserializerInterface;

interface DeserializerFactoryInterface
{
    public function getDeserializer(): DeserializerInterface;
}
