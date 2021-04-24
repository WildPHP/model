<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\TypeDefinitions;

class ArrayTypeDefinition implements TypeDefinitionInterface
{

    /**
     * @var \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface
     */
    private $contentDefinition;

    public function __construct(TypeDefinitionInterface $contentDefinition)
    {
        $this->contentDefinition = $contentDefinition;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$this->contentDefinition->validate($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        return [];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function toDefinition(): array
    {
        return [$this->contentDefinition->toDefinition()];
    }

    /**
     * @return \NanoSector\Models\TypeDefinitions\TypeDefinitionInterface
     */
    public function getContentDefinition(): TypeDefinitionInterface
    {
        return $this->contentDefinition;
    }

}
