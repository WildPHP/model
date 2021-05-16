<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests\Deserializers;

use DateTime;
use PHPUnit\Framework\TestCase;
use WildPHP\Models\Deserializers\DateTimeDeserializer;
use WildPHP\Models\Exceptions\DeserializationException;

/**
 * Class DateTimeDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \WildPHP\Models\Deserializers\DateTimeDeserializer
 */
class DateTimeDeserializerTest extends TestCase
{

    public function testCanDeserialize(): void
    {
        $deserializer = new DateTimeDeserializer();

        self::assertTrue($deserializer->canDeserialize(new DateTime()));
        self::assertTrue($deserializer->canDeserialize('2021-04-20'));
        self::assertTrue($deserializer->canDeserialize('12:00:00'));

        self::assertFalse($deserializer->canDeserialize('A random string'));
        self::assertFalse($deserializer->canDeserialize(42));
        self::assertFalse($deserializer->canDeserialize(true));
        self::assertFalse($deserializer->canDeserialize(5.1));
    }

    public function testDeserialize(): void
    {
        $deserializer = new DateTimeDeserializer();

        $currentDateTime = new DateTime();
        self::assertSame($currentDateTime, $deserializer->deserialize($currentDateTime));

        self::assertEquals(
            DateTime::createFromFormat(\DateTimeInterface::ATOM, '2005-08-15T15:52:01+00:00'),
            $deserializer->deserialize('2005-08-15T15:52:01+00:00')
        );

        self::assertEquals(
            DateTime::createFromFormat('!Y-m-d', '2021-04-20'),
            $deserializer->deserialize('2021-04-20')
        );

        self::assertEquals(
            DateTime::createFromFormat('H:i:s', '12:00:00'),
            $deserializer->deserialize('12:00:00')
        );
    }

    public function testDeserializeUnknownFormatThrowsException(): void
    {
        $deserializer = new DateTimeDeserializer();

        $this->expectException(DeserializationException::class);
        $deserializer->deserialize('this is wack');
    }
}
