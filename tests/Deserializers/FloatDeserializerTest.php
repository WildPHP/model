<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

namespace NanoSector\Models\Tests\Deserializers;

use NanoSector\Models\Deserializers\FloatDeserializer;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class FloatDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \NanoSector\Models\Deserializers\FloatDeserializer
 */
class FloatDeserializerTest extends TestCase
{

    public function testCanDeserialize(): void
    {
        $deserializer = new FloatDeserializer();
        self::assertTrue($deserializer->canDeserialize(999));
        self::assertTrue($deserializer->canDeserialize(42));
        self::assertTrue($deserializer->canDeserialize(5.6));
        self::assertTrue($deserializer->canDeserialize(-20));
        self::assertTrue($deserializer->canDeserialize('5.1'));
        self::assertTrue($deserializer->canDeserialize('42'));

        self::assertFalse($deserializer->canDeserialize(true));
        self::assertFalse($deserializer->canDeserialize(false));
        self::assertFalse($deserializer->canDeserialize('any random string'));
        self::assertFalse($deserializer->canDeserialize([]));
        self::assertFalse($deserializer->canDeserialize(new StdClass()));
    }

    public function testDeserialize(): void
    {
        $deserializer = new FloatDeserializer();
        self::assertSame(999.0, $deserializer->deserialize(999));
        self::assertSame(42.0, $deserializer->deserialize(42));
        self::assertSame(5.6, $deserializer->deserialize(5.6));
        self::assertSame(-20.0, $deserializer->deserialize(-20));
        self::assertSame(5.1, $deserializer->deserialize('5.1'));
        self::assertSame(42.0, $deserializer->deserialize('42'));
    }
}
