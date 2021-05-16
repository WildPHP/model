<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests\Deserializers;

use PHPUnit\Framework\TestCase;
use stdClass;
use WildPHP\Models\Deserializers\StringDeserializer;

/**
 * Class StringDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \WildPHP\Models\Deserializers\StringDeserializer
 */
class StringDeserializerTest extends TestCase
{
    public function testCanDeserialize(): void
    {
        $deserializer = new StringDeserializer();

        self::assertTrue($deserializer->canDeserialize(999));
        self::assertTrue($deserializer->canDeserialize(42));
        self::assertTrue($deserializer->canDeserialize(5.6));
        self::assertTrue($deserializer->canDeserialize(-20));
        self::assertTrue($deserializer->canDeserialize('5.1'));
        self::assertTrue($deserializer->canDeserialize('42'));
        self::assertTrue($deserializer->canDeserialize(true));
        self::assertTrue($deserializer->canDeserialize(false));
        self::assertTrue($deserializer->canDeserialize('any random string'));

        self::assertFalse($deserializer->canDeserialize([]));
        self::assertFalse($deserializer->canDeserialize(new StdClass()));
    }

    public function testDeserialize(): void
    {
        $deserializer = new StringDeserializer();
        self::assertSame('999', $deserializer->deserialize(999));
        self::assertSame('42', $deserializer->deserialize(42));
        self::assertSame('5.6', $deserializer->deserialize(5.6));
        self::assertSame('-20', $deserializer->deserialize(-20));
        self::assertSame('5.1', $deserializer->deserialize('5.1'));
        self::assertSame('42', $deserializer->deserialize('42'));
        self::assertSame('1', $deserializer->deserialize(true));
        self::assertSame('', $deserializer->deserialize(false));
        self::assertSame('any random string', $deserializer->deserialize('any random string'));
    }
}
