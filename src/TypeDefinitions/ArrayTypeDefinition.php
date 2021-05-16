<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\TypeDefinitions;

class ArrayTypeDefinition implements TypeDefinitionInterface
{

    /**
     * @var \WildPHP\Models\TypeDefinitions\TypeDefinitionInterface
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
     * @return array<string|array>
     */
    public function toDefinition(): array
    {
        return [$this->contentDefinition->toDefinition()];
    }

    /**
     * @return \WildPHP\Models\TypeDefinitions\TypeDefinitionInterface
     */
    public function getContentDefinition(): TypeDefinitionInterface
    {
        return $this->contentDefinition;
    }
}
