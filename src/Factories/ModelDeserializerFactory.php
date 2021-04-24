<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Factories;

use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Deserializers\ModelDeserializer;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\ReflectionHelper;

class ModelDeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var string
     */
    private $modelClass;

    /**
     * ModelDeserializerFactory constructor.
     *
     * @param string $modelClass
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $modelClass)
    {
        if (!ReflectionHelper::isModel($modelClass)) {
            throw new DeserializationInitializationException(
                'Given class is not a model class.'
            );
        }
        $this->modelClass = $modelClass;
    }

    /**
     * @return \NanoSector\Models\Deserializers\DeserializerInterface
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function getDeserializer(): DeserializerInterface
    {
        return new ModelDeserializer($this->modelClass);
    }
}
