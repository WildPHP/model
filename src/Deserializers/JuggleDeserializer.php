<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationException;
use NanoSector\Models\TypeDefinitions\PrimitiveTypeDefinition;

class JuggleDeserializer implements DeserializerInterface
{
    public const JUGGLE_TYPES = [
        PrimitiveTypeDefinition::STRING,
        PrimitiveTypeDefinition::INTEGER,
        PrimitiveTypeDefinition::FLOAT,
        PrimitiveTypeDefinition::BOOLEAN
    ];

    /**
     * @var string
     */
    private $primitiveType;

    /**
     * JuggleDeserializer constructor.
     *
     * @param string $primitiveType
     *
     * @see PrimitiveTypeDefinition::PRIMITIVE_TYPES
     */
    public function __construct(string $primitiveType)
    {
        $this->primitiveType = $primitiveType;
    }

    /**
     * @param string $value
     *
     * @return bool|float|int|string
     * @throws \NanoSector\Models\Exceptions\DeserializationException
     */
    public function deserialize($value)
    {
        switch ($this->primitiveType) {
            case PrimitiveTypeDefinition::BOOLEAN:
                return (bool)$value;

            case PrimitiveTypeDefinition::FLOAT:
                return (double)$value;

            case PrimitiveTypeDefinition::INTEGER:
                return (int)$value;

            case PrimitiveTypeDefinition::STRING:
                return (string)$value;

            default:
                throw new DeserializationException(
                    'Cannot deserialize type ' . $this->primitiveType . ' by type juggling'
                );
        }
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return true;
    }
}
