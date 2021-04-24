<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\TypeDefinitions;

interface TypeDefinitionInterface
{

    /**
     * Validates that a given value passes this type definition's checks.
     *
     * @param mixed $value
     *
     * @return bool true if the value passes, false otherwise.
     */
    public function validate($value): bool;

    /**
     * Returns a default value for this type definition.
     *
     * @return mixed
     */
    public function default();

    /**
     * Returns the readable representation of this type definition.
     *
     * @return mixed
     */
    public function toDefinition();

}
