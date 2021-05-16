<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Factories;

use WildPHP\Models\Deserializers\DeserializerInterface;

interface DeserializerFactoryInterface
{
    public function getDeserializer(): DeserializerInterface;
}
