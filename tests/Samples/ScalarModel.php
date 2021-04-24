<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\Tests\Samples;


use NanoSector\Models\Model;
use NanoSector\Models\TypeDefinitions\GetTypeTypeDefinition;

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
      'string' => GetTypeTypeDefinition::STRING,
      'int' => GetTypeTypeDefinition::INTEGER,
      'bool' => GetTypeTypeDefinition::BOOLEAN,
      'float' => GetTypeTypeDefinition::FLOAT,
      'null' => GetTypeTypeDefinition::NULL,
    ];
}
