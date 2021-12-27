<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Factories;

use WildPHP\Models\Deserializers\ArrayDeserializer;
use WildPHP\Models\Deserializers\DeserializerInterface;

class ArrayDeserializerFactory implements DeserializerFactoryInterface
{
    /**
     * @var \WildPHP\Models\Factories\DeserializerFactoryInterface
     */
    private $parentFactory;

    /**
     * ArrayDeserializerFactory constructor.
     *
     * @param \WildPHP\Models\Factories\DeserializerFactoryInterface $parentFactory
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
