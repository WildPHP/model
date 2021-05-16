<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Deserializers;

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
