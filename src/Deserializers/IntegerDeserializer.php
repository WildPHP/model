<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

/**
 * Class IntegerDeserializer
 *
 * @package NanoSector\Models\Deserializers
 */
class IntegerDeserializer implements DeserializerInterface
{
    /**
     * @inheritDoc
     */
    public function deserialize($value)
    {
        return (int)$value;
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return is_numeric($value);
    }
}
