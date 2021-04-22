<?php


namespace NanoSector\Models\Factories;


use NanoSector\Models\Deserializers\DeserializerInterface;
use NanoSector\Models\Deserializers\ModelDeserializer;
use NanoSector\Models\Exceptions\DeserializationInitializationException;
use NanoSector\Models\Helpers\DeserializerHelper;

class ModelDeserializerFactory implements DeserializerFactoryInterface
{

    /**
     * @var string
     */
    private $modelClass;

    /**
     * ModelDeserializerFactory constructor.
     *
     * @param  string  $modelClass
     *
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function __construct(string $modelClass)
    {
        if (!DeserializerHelper::isModel($modelClass)) {
            throw new DeserializationInitializationException(
              'Given class is not a model class.'
            );
        }
        $this->modelClass = $modelClass;
    }

    /**
     * @return \NanoSector\Models\Deserializers\DeserializerInterface
     * @throws \NanoSector\Models\Exceptions\DeserializationInitializationException
     */
    public function getDeserializer(): DeserializerInterface
    {
        return new ModelDeserializer($this->modelClass);
    }

}
