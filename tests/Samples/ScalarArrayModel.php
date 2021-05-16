<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests\Samples;

use WildPHP\Models\Model;
use WildPHP\Models\TypeDefinitions\PrimitiveTypeDefinition;

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
    protected $structure = [
      'strings' => [PrimitiveTypeDefinition::STRING],
      'ints' => [PrimitiveTypeDefinition::INTEGER],
      'bools' => [PrimitiveTypeDefinition::BOOLEAN],
      'floats' => [PrimitiveTypeDefinition::FLOAT],
      'nulls' => [PrimitiveTypeDefinition::NULL],
    ];
}
