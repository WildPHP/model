<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Deserializers\ArrayDeserializer;
use NanoSector\Models\Deserializers\DeserializerInterface;

class ArrayDeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var \NanoSector\Models\Factories\DeserializerFactoryInterface
     */
    private $parentFactory;

    /**
     * ArrayDeserializerFactory constructor.
     *
     * @param \NanoSector\Models\Factories\DeserializerFactoryInterface $parentFactory
     */
    public function __construct(DeserializerFactoryInterface $parentFactory)
    {
        $this->parentFactory = $parentFactory;
    }

    public function getDeserializer(): DeserializerInterface
    {
        return new ArrayDeserializer($this->parentFactory->getDeserializer());
    }
}
