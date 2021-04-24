<?php

/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models\Deserializers;

use DateTime;
use DateTimeInterface;
use NanoSector\Models\Exceptions\DeserializationException;

class DateTimeDeserializer implements DeserializerInterface
{

    /**
     * @param mixed $value
     *
     * @return \DateTimeInterface
     * @throws \NanoSector\Models\Exceptions\DeserializationException
     */
    public function deserialize($value): DateTimeInterface
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        // Try ISO8601 first.
        $date = DateTime::createFromFormat(DateTimeInterface::ATOM, $value)
            // Then a simple date format...
            ?: DateTime::createFromFormat('!Y-m-d', $value)
                // And finally just time.
                ?: DateTime::createFromFormat('H:i:s', $value);

        if ($date === false) {
            throw new DeserializationException('Cannot deserialize given date');
        }

        return $date;
    }

    /**
     * @inheritDoc
     */
    public function canDeserialize($value): bool
    {
        return is_string($value);
    }
}
