<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Helpers\BooleanHelper;

/**
 * Class BooleanDeserializer
 *
 * @package NanoSector\Models\Deserializers
 */
class BooleanDeserializer implements DeserializerInterface
{
    /**
     * @inheritDoc
     */
    public function deserialize($value)
    {
        return is_string($value) ? BooleanHelper::stringToBoolean($value) : (bool)$value;
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return is_bool($value) || (is_scalar($value) && BooleanHelper::isBooleanRepresentative($value));
    }
}
