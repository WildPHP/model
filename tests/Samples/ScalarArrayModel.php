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
 * Class ScalarArrayModel
 *
 * @package NanoSector\Models\Tests\Samples
 *
 * @property string[] $strings
 * @property int[] $ints
 * @property bool[] $bools
 * @property float[] $floats
 * @property null[] $nulls
 */
class ScalarArrayModel extends Model
{
    protected $settable = [
      'strings' => [PrimitiveTypeDefinition::STRING],
      'ints' => [PrimitiveTypeDefinition::INTEGER],
      'bools' => [PrimitiveTypeDefinition::BOOLEAN],
      'floats' => [PrimitiveTypeDefinition::FLOAT],
      'nulls' => [PrimitiveTypeDefinition::NULL],
    ];
}
