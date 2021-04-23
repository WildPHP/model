<?php

namespace NanoSector\Models\Tests;

use NanoSector\Models\Model;
use NanoSector\Models\Tests\Samples\ScalarModel;
use PHPUnit\Framework\TestCase;

class ScalarModelTest extends TestCase
{

    public function testCanSetScalarTypesOnModel()
    {
        $model = new ScalarModel();

        $model->null = null;
        $model->float = 5.1;
        $model->string = 'test';
        $model->bool = true;
        $model->int = 100;

        self::assertEquals(null, $model->null);
        self::assertEquals(5.1, $model->float);
        self::assertEquals('test', $model->string);
        self::assertEquals(true, $model->bool);
        self::assertEquals(100, $model->int);

    }
}
