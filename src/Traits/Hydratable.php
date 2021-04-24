<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Traits;

trait Hydratable
{
    /**
     * Properties which may be mass assigned or hydrated.
     * Setting this when guarded is set will override guarded.
     *
     * @var string[]
     * @see \NanoSector\Models\Model::$guarded
     */
    protected $fillable = [];

    /**
     * Properties which may not be mass assigned or hydrated.
     * Setting this when fillable is set has no effect.
     *
     * @var string[]
     * @see \NanoSector\Models\Model::$fillable
     */
    protected $guarded = [];

    /**
     * Checks whether the given key can be mass assigned.
     *
     * @param string $key
     *
     * @return bool
     */
    public function canHydrate(string $key): bool
    {
        return empty($this->fillable) ? !in_array(
            $key,
            $this->guarded
        ) : in_array($key, $this->fillable);
    }

    /**
     * Mass assigns this model instance with the given properties.
     *
     * @param array<string, mixed> $properties
     */
    public function hydrate(array $properties): void
    {
        if (empty($properties)) {
            return;
        }

        foreach ($properties as $key => $value) {
            if (!$this->canHydrate($key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}
