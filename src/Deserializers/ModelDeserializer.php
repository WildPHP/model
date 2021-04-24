<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\ReflectionHelper;
use NanoSector\Models\Model;

class ModelDeserializer implements DeserializerInterface
{

    /**
     * @var string
     */
    private $modelClass;

    /**
     * ModelDeserializer constructor.
     *
     * @param string $modelClass
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $modelClass)
    {
        if (!ReflectionHelper::isModel($modelClass)) {
            throw new DeserializationInitializationException(
                'Cannot create a deserializer out of non-model class ' . $modelClass
            );
        }

        $this->modelClass = $modelClass;
    }

    /**
     * @param array<string, mixed> $value
     *
     * @return \NanoSector\Models\Model
     */
    public function deserialize($value): Model
    {
        return new $this->modelClass($value);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function canDeserialize($value): bool
    {
        return is_array($value);
    }
}
