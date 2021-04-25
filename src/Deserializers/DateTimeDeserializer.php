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

/**
 * Class DateTimeDeserializer
 *
 * @package NanoSector\Models\Deserializers
 */
class DateTimeDeserializer implements DeserializerInterface
{
    /**
     * Regex for matching a date in ISO8601 format.
     */
    public const ATOM_REGEX = /** @lang RegExp */
        '/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}/';

    /**
     * Regex for matching a date in format YYYY-MM-DD (Y-m-d)
     */
    public const DATE_REGEX = /** @lang RegExp */
        '/\d{4}-\d{2}-\d{2}/';

    /**
     * Regex for matching a time string in format HH:MM:SS (H:i:s)
     */
    public const TIME_REGEX = /** @lang RegExp */
        '/\d{2}:\d{2}:\d{2}/';

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
        return $value instanceof DateTimeInterface
            || (is_string($value)
                && (
                    preg_match(self::ATOM_REGEX, $value) === 1
                    || preg_match(self::DATE_REGEX, $value) === 1
                    || preg_match(self::TIME_REGEX, $value) === 1
                ));
    }
}
