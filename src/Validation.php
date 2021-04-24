<?php
/*
 * Copyright 2021 NanoSector
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace NanoSector\Models;

use InvalidArgumentException;

/**
 * Class Validation
 *
 * Type validation utilities.
 *
 * @package NanoSector\Models
 */
class Validation
{

    /**
     * Possible values for gettype()
     *
     * @var string[]
     * @see \gettype()
     */
    public const GETTYPE_VALUES = [
        'boolean',
        'integer',
        'double',
        'string',
        'array',
        'object',
        'resource',
        'resource (closed)',
        'NULL',
    ];

    /**
     * Validates an array based on its values and optionally keys.
     *
     * @param array       $array   the array to validate
     * @param string      $type    the type to check for
     * @param string|null $keyType the key type to check for, or null to not
     *                             check keys
     *
     * @return bool
     * @see isOfType()
     */
    public static function isArrayOfType(
        array $array,
        string $type,
        string $keyType = null
    ): bool {
        foreach ($array as $key => $value) {
            if (!self::isOfType(
                    $value,
                    $type
                ) || ($keyType !== null && !self::isOfType($key, $keyType))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the given value is of the specified type.
     *
     * @param mixed  $value the value to check
     * @param string $type  the type of the value according to gettype(), or
     *                      a class name.
     *
     * @return bool
     * @throws InvalidArgumentException when an invalid type was passed
     * @see gettype()
     */
    public static function isOfType($value, string $type): bool
    {
        if (class_exists($type)) {
            return $value instanceof $type;
        }

        if (!in_array($type, self::GETTYPE_VALUES)) {
            throw new InvalidArgumentException(
                'Unknown type ' . $type . ' passed.'
            );
        }

        return gettype($value) === $type;
    }

    /**
     * Gets the default value for a type.
     *
     * @param string $type
     *
     * @return array|false|float|int|string|null
     * @see          gettype()
     * @noinspection MultipleReturnStatementsInspection
     */
    public static function defaultTypeValue(string $type)
    {
        switch ($type) {
            case 'boolean':
                return false;

            case 'integer':
                return 0;

            case 'double':
                return 0.0;

            case 'string':
                return '';

            case 'array':
                return [];

            default:
                return null;
        }
    }

}
