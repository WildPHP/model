<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

interface DeserializerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function deserialize($value);

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function canDeserialize($value): bool;
}
