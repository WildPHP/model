<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Tests\Samples;

use NanoSector\Models\Model;
use NanoSector\Models\TypeDefinitions\PrimitiveTypeDefinition;

/**
 * Class ScalarModel
 *
 * @package NanoSector\Models\Tests\Samples
 *
 * @property string $string
 * @property int $int
 * @property bool $bool
 * @property float $float
 * @property null $null
 */
class ScalarModel extends Model
{
    protected $settable = [
      'string' => PrimitiveTypeDefinition::STRING,
      'int' => PrimitiveTypeDefinition::INTEGER,
      'bool' => PrimitiveTypeDefinition::BOOLEAN,
      'float' => PrimitiveTypeDefinition::FLOAT,
      'null' => PrimitiveTypeDefinition::NULL,
    ];
}
