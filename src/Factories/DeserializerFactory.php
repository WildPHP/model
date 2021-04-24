<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\DeserializerHelper;

class DeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $className)
    {
        if (!DeserializerHelper::isDeserializer($className)) {
            throw new DeserializationInitializationException(
                'Given class is not a deserializer.'
            );
        }

        $this->className = $className;
    }

    public function getDeserializer(): DeserializerInterface
    {
        return new $this->className();
    }

}
