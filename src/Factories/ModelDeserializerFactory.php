<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Factories;

use WildPHP\Models\Deserializers\DeserializerInterface;
use WildPHP\Models\Deserializers\ModelDeserializer;
use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Helpers\ReflectionHelper;

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
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
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
     * @return \WildPHP\Models\Deserializers\DeserializerInterface
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
     */
    public function getDeserializer(): DeserializerInterface
    {
        return new ModelDeserializer($this->modelClass);
    }
}
