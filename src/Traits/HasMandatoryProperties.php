<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Traits;

trait HasMandatoryProperties
{
    /**
     * A list of mandatory properties by key.
     *
     * @var string[]
     */
    protected $mandatory = [];

    /**
     * Checks whether all mandatory properties exist in the given array.
     *
     * @param array<string, mixed> $array
     *
     * @return bool
     */
    public function satisfiesMandatoryProperties(array $array): bool
    {
        return array_intersect(array_keys($array), $this->mandatory) === $this->mandatory;
    }
}
