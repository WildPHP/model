<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use NanoSector\Models\Exceptions\DeserializationException;
use NanoSector\Models\TypeDefinitions\GetTypeTypeDefinition;

class JuggleDeserializer implements DeserializerInterface
{
    public const JUGGLE_TYPES = [
        GetTypeTypeDefinition::STRING,
        GetTypeTypeDefinition::INTEGER,
        GetTypeTypeDefinition::FLOAT,
        GetTypeTypeDefinition::BOOLEAN
    ];

    /**
     * @var string
     */
    private $getTypeType;

    /**
     * JuggleDeserializer constructor.
     *
     * @param string $getTypeType
     *
     * @see GetTypeTypeDefinition::GETTYPE_VALUES
     */
    public function __construct(string $getTypeType)
    {
        $this->getTypeType = $getTypeType;
    }

    /**
     * @param string $value
     *
     * @return bool|float|int|string
     * @throws \NanoSector\Models\Exceptions\DeserializationException
     */
    public function deserialize($value)
    {
        switch ($this->getTypeType) {
            case GetTypeTypeDefinition::BOOLEAN:
                return (bool)$value;

            case GetTypeTypeDefinition::FLOAT:
                return (double)$value;

            case GetTypeTypeDefinition::INTEGER:
                return (int)$value;

            case GetTypeTypeDefinition::STRING:
                return (string)$value;

            default:
                throw new DeserializationException(
                    'Cannot deserialize type ' . $this->getTypeType . ' by type juggling'
                );
        }
    }

    public function canDeserialize($value): bool
    {
        return true;
    }
}
