<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Tests;

use NanoSector\Models\Exceptions\ModelException;
use NanoSector\Models\Tests\Samples\ScalarArrayModel;
use NanoSector\Models\TypeDefinitions\ArrayTypeDefinition;
use NanoSector\Models\TypeDefinitions\PrimitiveTypeDefinition;
use PHPUnit\Framework\TestCase;

/**
 * Class ScalarArrayModelTest
 *
 * @package NanoSector\Models\Tests
 * @covers  \NanoSector\Models\Model
 * @uses    \NanoSector\Models\TypeDefinitions\ArrayTypeDefinition
 * @uses    \NanoSector\Models\TypeDefinitions\PrimitiveTypeDefinition
 * @uses    \NanoSector\Models\TypeDefinitions\TypeDefinitionInterpreter
 */
class ScalarArrayModelTest extends TestCase
{
    public function testStructureIsUntouched(): void
    {
        $model = new ScalarArrayModel();

        self::assertEquals(
            [
                'strings' => [PrimitiveTypeDefinition::STRING],
                'ints' => [PrimitiveTypeDefinition::INTEGER],
                'bools' => [PrimitiveTypeDefinition::BOOLEAN],
                'floats' => [PrimitiveTypeDefinition::FLOAT],
                'nulls' => [PrimitiveTypeDefinition::NULL],
            ],
            $model->getStructure()
        );
    }

    public function testGeneratedTypeDefinitionMap(): void
    {
        $model = new ScalarArrayModel();
        $typeDefinitionMap = $model->getTypeDefinitionMap();

        self::assertCount(5, $typeDefinitionMap);

        $boolDefinition = $typeDefinitionMap['bools'];
        $floatDefinition = $typeDefinitionMap['floats'];
        $intDefinition = $typeDefinitionMap['ints'];
        $nullDefinition = $typeDefinitionMap['nulls'];
        $stringDefinition = $typeDefinitionMap['strings'];

        self::assertInstanceOf(ArrayTypeDefinition::class, $boolDefinition);
        self::assertInstanceOf(ArrayTypeDefinition::class, $floatDefinition);
        self::assertInstanceOf(ArrayTypeDefinition::class, $intDefinition);
        self::assertInstanceOf(ArrayTypeDefinition::class, $nullDefinition);
        self::assertInstanceOf(ArrayTypeDefinition::class, $stringDefinition);
    }

    public function testCanSetScalarArrayTypesOnModel(): void
    {
        $model = new ScalarArrayModel();

        $model->nulls = [null];
        $model->floats = [5.1];
        $model->strings = ['test'];
        $model->bools = [true];
        $model->ints = [100];

        self::assertEquals([null], $model->nulls);
        self::assertEquals([5.1], $model->floats);
        self::assertEquals(['test'], $model->strings);
        self::assertEquals([true], $model->bools);
        self::assertEquals([100], $model->ints);
    }

    public function testCanHydrateModel(): void
    {
        $model = new ScalarArrayModel(
            [
                'nulls' => [null],
                'floats' => [5.1],
                'strings' => ['test'],
                'bools' => [true],
                'ints' => [100],
            ]
        );

        self::assertEquals([null], $model->nulls);
        self::assertEquals([5.1], $model->floats);
        self::assertEquals(['test'], $model->strings);
        self::assertEquals([true], $model->bools);
        self::assertEquals([100], $model->ints);
    }

    public function testCannotHydrateModelWithInvalidParameters(): void
    {
        $this->expectException(ModelException::class);
        new ScalarArrayModel(
            [
                'int' => 'definitely not an int',
            ]
        );
    }

    public function testCannotHydrateModelWithUnknownParameters(): void
    {
        $this->expectException(ModelException::class);
        new ScalarArrayModel(
            [
                'nonExistentProperty' => 'a random value',
            ]
        );
    }

    public function testAddDefaultsDoesNotOverwriteSetKeys()
    {
        $model = new ScalarArrayModel();

        $model->ints = [100];
        $model->addDefaults();

        self::assertEquals([100], $model->ints);
    }
}
