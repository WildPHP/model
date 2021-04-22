<?php

namespace NanoSector\Models\Deserializers;

use DateTime;
use DateTimeInterface;
use NanoSector\Models\Exceptions\DeserializationException;

class DateTimeDeserializer implements DeserializerInterface
{

    /**
     * @param  mixed  $value
     *
     * @return \DateTime
     * @throws \NanoSector\Models\Exceptions\DeserializationException
     */
    public function deserialize($value): DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        // Try ISO8601 first.
        $date = DateTime::createFromFormat(DateTimeInterface::ATOM, $value)
          ?: DateTime::createFromFormat('!Y-m-d', $value)
            ?: DateTime::createFromFormat('H:i:s', $value);

        if ($date === false) {
            throw new DeserializationException('Cannot deserialize given date');
        }

        return $date;
    }

    public function canDeserialize($value): bool
    {
        return is_string($value);
    }

}
