<?php


namespace NanoSector\Models\Tests\Samples;


use NanoSector\Models\Model;
use NanoSector\Models\TypeDefinitions\GetTypeTypeDefinition;

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
      'strings' => [GetTypeTypeDefinition::STRING],
      'ints' => [GetTypeTypeDefinition::INTEGER],
      'bools' => [GetTypeTypeDefinition::BOOLEAN],
      'floats' => [GetTypeTypeDefinition::FLOAT],
      'nulls' => [GetTypeTypeDefinition::NULL],
    ];
}
