<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Tests\Deserializers;

use PHPUnit\Framework\TestCase;
use WildPHP\Models\Deserializers\ArrayDeserializer;
use WildPHP\Models\Deserializers\FloatDeserializer;
use WildPHP\Models\Exceptions\DeserializationException;

/**
 * Class ArrayDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \WildPHP\Models\Deserializers\ArrayDeserializer
 * @uses    \WildPHP\Models\Deserializers\FloatDeserializer
 */
class ArrayDeserializerTest extends TestCase
{
    /**
     * @var \WildPHP\Models\Deserializers\ArrayDeserializer
     */
    private $deserializer;

    protected function setUp(): void
    {
        $this->deserializer = new ArrayDeserializer(
            new FloatDeserializer()
        );
    }

    public function testCanDeserialize(): void
    {
        self::assertTrue($this->deserializer->canDeserialize([]));
        self::assertTrue($this->deserializer->canDeserialize(['1']));
        self::assertTrue($this->deserializer->canDeserialize([1]));
        self::assertTrue($this->deserializer->canDeserialize([1, 2]));
        self::assertTrue($this->deserializer->canDeserialize([1, 2, 3]));

        self::assertFalse($this->deserializer->canDeserialize(['test']));
        self::assertFalse($this->deserializer->canDeserialize('string'));
        self::assertFalse($this->deserializer->canDeserialize(1));
        self::assertFalse($this->deserializer->canDeserialize([[]]));
        self::assertFalse($this->deserializer->canDeserialize([[1]]));
        self::assertFalse($this->deserializer->canDeserialize([['string']]));
    }

    public function testDeserialize(): void
    {
        self::assertEquals([1], $this->deserializer->deserialize([1]));
        self::assertEquals([1], $this->deserializer->deserialize(['1']));
    }

    public function testDeserializeReturnsEmptyArrayOnNonArrayType(): void
    {
        self::assertEquals([], $this->deserializer->deserialize(1));
        self::assertEquals([], $this->deserializer->deserialize('so i was walking down the park one day'));
        self::assertEquals([], $this->deserializer->deserialize('and a gnome attacked me'));
    }

    public function testDeserializeThrowsExceptionWhenArrayContainsInvalidItems(): void
    {
        $this->expectException(DeserializationException::class);
        $this->deserializer->deserialize([[1]]);
    }
}
