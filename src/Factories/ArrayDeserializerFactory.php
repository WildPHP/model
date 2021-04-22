<?php


namespace NanoSector\Models\Factories;


use NanoSector\Models\Deserializers\ArrayDeserializer;
use NanoSector\Models\Deserializers\DeserializerInterface;

class ArrayDeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var \NanoSector\Models\Factories\DeserializerFactoryInterface
     */
    private $parentDeserializerFactory;

    /**
     * ArrayDeserializerFactory constructor.
     *
     * @param  \NanoSector\Models\Factories\DeserializerFactoryInterface  $parentDeserializerFactory
     */
    public function __construct(DeserializerFactoryInterface $parentDeserializerFactory)
    {
        $this->parentDeserializerFactory = $parentDeserializerFactory;
    }

    public function getDeserializer(): DeserializerInterface
    {
        return new ArrayDeserializer($this->parentDeserializerFactory->getDeserializer());
    }

}
