<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Deserializers;

use WildPHP\Models\Exceptions\DeserializationInitializationException;
use WildPHP\Models\Helpers\ReflectionHelper;
use WildPHP\Models\Model;

/**
 * Deserializer for Model classes
 */
class ModelDeserializer implements DeserializerInterface
{
    /**
     * @var class-string<\WildPHP\Models\Model>
     */
    private $modelClass;

    /**
     * ModelDeserializer constructor.
     *
     * @param class-string<\WildPHP\Models\Model> $modelClass
     *
     * @throws \WildPHP\Models\Exceptions\DeserializationInitializationException
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
     * @return \WildPHP\Models\Model
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
