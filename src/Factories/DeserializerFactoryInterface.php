<?php


namespace NanoSector\Models\Factories;


use NanoSector\Models\Deserializers\DeserializerInterface;

interface DeserializerFactoryInterface
{
    public function getDeserializer(): DeserializerInterface;
}
