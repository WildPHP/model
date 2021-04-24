<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\TypeDefinitions;

use NanoSector\Models\Exceptions\TypeDefinitionException;

class ClassTypeDefinition implements TypeDefinitionInterface
{

    /**
     * @var string
     */
    private $wantedClass;

    /**
     * ClassTypeDefinition constructor.
     *
     * @param string $wantedClass
     *
     * @throws \NanoSector\Models\Exceptions\TypeDefinitionException
     */
    public function __construct(string $wantedClass)
    {
        if (!class_exists($wantedClass)) {
            throw new TypeDefinitionException(
                'The wanted class does not exist in ClassTypeDefinition'
            );
        }
        $this->wantedClass = $wantedClass;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return $value === null || $value instanceof $this->wantedClass;
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        return null;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function toDefinition(): string
    {
        return $this->wantedClass;
    }

}
