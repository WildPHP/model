<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests\Samples;

use WildPHP\Models\Model;
use WildPHP\TypeDefinitions\PrimitiveTypeDefinition;

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
    protected $structure = [
      'string' => PrimitiveTypeDefinition::STRING,
      'int' => PrimitiveTypeDefinition::INTEGER,
      'bool' => PrimitiveTypeDefinition::BOOLEAN,
      'float' => PrimitiveTypeDefinition::FLOAT,
      'null' => PrimitiveTypeDefinition::NULL,
    ];
}
