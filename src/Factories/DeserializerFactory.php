<?php


namespace NanoSector\Models\Factories;


use NanoSector\Models\Deserializers\ArrayDeserializer;
use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Deserializers\ModelDeserializer;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\DeserializerHelper;

class DeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var string
     */
    private $className;

    /**
     * @param  string  $className
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $className)
    {
        if (!DeserializerHelper::isDeserializer($className)) {
            throw new DeserializationInitializationException(
              'Given class is not a deserializer.'
            );
        }

        $this->className = $className;
    }

    public function getDeserializer(): DeserializerInterface
    {
        return new $this->className();
    }

}
