<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests;

use PHPUnit\Framework\TestCase;
use WildPHP\Models\Exceptions\ModelException;
use WildPHP\Models\Tests\Samples\ScalarModel;
use WildPHP\Models\TypeDefinitions\PrimitiveTypeDefinition;

/**
 * Class ScalarModelTest
 *
 * @package NanoSector\Models\Tests
 * @covers  \WildPHP\Models\Model
 * @uses    \WildPHP\Models\TypeDefinitions\PrimitiveTypeDefinition
 * @uses    \WildPHP\Models\TypeDefinitions\TypeDefinitionInterpreter
 */
class ScalarModelTest extends TestCase
{
    public function testStructureIsUntouched(): void
    {
        $model = new ScalarModel();

        self::assertEquals(
            [
                'string' => PrimitiveTypeDefinition::STRING,
                'int' => PrimitiveTypeDefinition::INTEGER,
                'bool' => PrimitiveTypeDefinition::BOOLEAN,
                'float' => PrimitiveTypeDefinition::FLOAT,
                'null' => PrimitiveTypeDefinition::NULL,
            ],
            $model->getStructure()
        );
    }

    public function testGeneratedTypeDefinitionMap(): void
    {
        $model = new ScalarModel();
        $typeDefinitionMap = $model->getTypeDefinitionMap();

        self::assertCount(5, $typeDefinitionMap);

        $boolDefinition = $typeDefinitionMap['bool'];
        $floatDefinition = $typeDefinitionMap['float'];
        $intDefinition = $typeDefinitionMap['int'];
        $nullDefinition = $typeDefinitionMap['null'];
        $stringDefinition = $typeDefinitionMap['string'];

        self::assertInstanceOf(PrimitiveTypeDefinition::class, $boolDefinition);
        self::assertInstanceOf(PrimitiveTypeDefinition::class, $floatDefinition);
        self::assertInstanceOf(PrimitiveTypeDefinition::class, $intDefinition);
        self::assertInstanceOf(PrimitiveTypeDefinition::class, $nullDefinition);
        self::assertInstanceOf(PrimitiveTypeDefinition::class, $stringDefinition);
    }

    public function testCanSetScalarTypesOnModel(): void
    {
        $model = new ScalarModel();

        $model->null = null;
        $model->float = 5.1;
        $model->string = 'test';
        $model->bool = true;
        $model->int = 100;

        self::assertTrue(isset($model->null));
        self::assertTrue(isset($model->float));
        self::assertTrue(isset($model->string));
        self::assertTrue(isset($model->bool));
        self::assertTrue(isset($model->int));
        self::assertFalse(isset($model->what));

        self::assertEquals(null, $model->null);
        self::assertEquals(5.1, $model->float);
        self::assertEquals('test', $model->string);
        self::assertEquals(true, $model->bool);
        self::assertEquals(100, $model->int);
    }

    public function testCanHydrateModel(): void
    {
        $model = new ScalarModel(
            [
                'null' => null,
                'float' => 5.1,
                'string' => 'test',
                'bool' => true,
                'int' => 100,
            ]
        );

        self::assertEquals(null, $model->null);
        self::assertEquals(5.1, $model->float);
        self::assertEquals('test', $model->string);
        self::assertEquals(true, $model->bool);
        self::assertEquals(100, $model->int);
    }

    public function testCannotHydrateModelWithInvalidParameters(): void
    {
        $this->expectException(ModelException::class);
        new ScalarModel(
            [
                'int' => 'definitely not an int',
            ]
        );
    }

    public function testCannotHydrateModelWithUnknownParameters(): void
    {
        $this->expectException(ModelException::class);
        new ScalarModel(
            [
                'nonExistentProperty' => 'a random value',
            ]
        );
    }

    public function testAddDefaultsDoesNotOverwriteSetKeys()
    {
        $model = new ScalarModel();

        $model->int = 100;
        $model->addDefaults();

        self::assertEquals(100, $model->int);
    }

    public function testCannotSetInvalidTypesOnModel(): void
    {
        $model = new ScalarModel();

        $this->expectException(ModelException::class);
        $model->null = 'this is definitely not null';
    }

    public function testCannotSetUnknownPropertyOnModel(): void
    {
        $model = new ScalarModel();

        self::assertFalse($model->isPropertyKnown('nonExistentProperty'));
        self::assertFalse($model->canAssignValue('nonExistentProperty', 'a value'));

        $this->expectException(ModelException::class);
        $model->nonExistentProperty = 'a value because it really does not matter';
    }

    public function testCannotGetUnknownPropertyFromModel(): void
    {
        $model = new ScalarModel();

        $this->expectException(ModelException::class);
        $model->nonExistentProperty;
    }

    public function testToArrayReturnsProperties()
    {
        $model = new ScalarModel(
            [
                'null' => null,
                'float' => 5.1,
                'string' => 'test',
                'bool' => true,
                'int' => 100,
            ]
        );

        self::assertEquals(
            [
                'null' => null,
                'float' => 5.1,
                'string' => 'test',
                'bool' => true,
                'int' => 100,
            ],
            $model->toArray()
        );
    }
}
