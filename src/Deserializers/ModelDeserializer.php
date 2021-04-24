<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\DeserializerHelper;

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
        if (!DeserializerHelper::isModel($modelClass)) {
            throw new DeserializationInitializationException(
                'Cannot create a deserializer out of non-model class ' . $modelClass
            );
        }

        $this->modelClass = $modelClass;
    }

    /**
     * @param array $value
     *
     * @return mixed
     */
    public function deserialize($value)
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
