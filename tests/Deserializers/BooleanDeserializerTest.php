<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

namespace WildPHP\Models\Tests\Deserializers;

use PHPUnit\Framework\TestCase;
use stdClass;
use WildPHP\Models\Deserializers\BooleanDeserializer;

/**
 * Class BooleanDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \WildPHP\Models\Deserializers\BooleanDeserializer
 * @uses    \WildPHP\Models\Helpers\BooleanHelper
 */
class BooleanDeserializerTest extends TestCase
{
    public function testCanDeserialize(): void
    {
        $deserializer = new BooleanDeserializer();
        self::assertTrue($deserializer->canDeserialize(true));
        self::assertTrue($deserializer->canDeserialize(false));
        self::assertTrue($deserializer->canDeserialize(1));
        self::assertTrue($deserializer->canDeserialize(0));
        self::assertTrue($deserializer->canDeserialize('1'));
        self::assertTrue($deserializer->canDeserialize('0'));
        self::assertTrue($deserializer->canDeserialize('true'));
        self::assertTrue($deserializer->canDeserialize('false'));
        self::assertTrue($deserializer->canDeserialize('yes'));
        self::assertTrue($deserializer->canDeserialize('no'));

        self::assertFalse($deserializer->canDeserialize('any random string'));
        self::assertFalse($deserializer->canDeserialize(999));
        self::assertFalse($deserializer->canDeserialize(42));
        self::assertFalse($deserializer->canDeserialize(5.6));
        self::assertFalse($deserializer->canDeserialize(-20));
        self::assertFalse($deserializer->canDeserialize([]));
        self::assertFalse($deserializer->canDeserialize(new StdClass()));
    }

    public function testDeserialize(): void
    {
        $deserializer = new BooleanDeserializer();
        self::assertTrue($deserializer->deserialize(true));
        self::assertTrue($deserializer->deserialize(1));
        self::assertTrue($deserializer->deserialize('1'));
        self::assertTrue($deserializer->deserialize('true'));
        self::assertTrue($deserializer->deserialize('yes'));

        self::assertFalse($deserializer->deserialize(false));
        self::assertFalse($deserializer->deserialize(0));
        self::assertFalse($deserializer->deserialize('0'));
        self::assertFalse($deserializer->deserialize('false'));
        self::assertFalse($deserializer->deserialize('no'));
    }
}
