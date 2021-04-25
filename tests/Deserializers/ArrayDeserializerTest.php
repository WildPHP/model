<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Tests\Deserializers;

use NanoSector\Models\Deserializers\ArrayDeserializer;
use NanoSector\Models\Deserializers\GlobalDeserializerRegistry;
use NanoSector\Models\Exceptions\DeserializationException;
use NanoSector\Models\TypeDefinitions\PrimitiveTypeDefinition;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayDeserializerTest
 *
 * @package NanoSector\Models\Tests\Deserializers
 * @covers  \NanoSector\Models\Deserializers\ArrayDeserializer
 * @uses    \NanoSector\Models\Deserializers\FloatDeserializer
 * @uses    \NanoSector\Models\Deserializers\GlobalDeserializerRegistry
 */
class ArrayDeserializerTest extends TestCase
{
    /**
     * @var \NanoSector\Models\Deserializers\ArrayDeserializer
     */
    private $deserializer;

    protected function setUp(): void
    {
        GlobalDeserializerRegistry::juggle();

        $childDeserializer = GlobalDeserializerRegistry::get(PrimitiveTypeDefinition::FLOAT);

        if (is_null($childDeserializer)) {
            self::fail('Unrelated error: Could not create child definition.');
        }

        $this->deserializer = new ArrayDeserializer(
            $childDeserializer
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

    public function testDeserializeThrowsExceptionWhenArrayContainsInvalidItems()
    {
        $this->expectException(DeserializationException::class);
        $this->deserializer->deserialize([[1]]);
    }
}
