<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\TypeDefinitions;

use WildPHP\Models\Exceptions\TypeDefinitionException;

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
     * @throws \WildPHP\Models\Exceptions\TypeDefinitionException
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
