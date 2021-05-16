<?php

/*
 * Copyright 2021 The WildPHP Team
 * See LICENSE.md in the project root.
 */

declare(strict_types=1);

namespace WildPHP\Models\Helpers;

class BooleanHelper
{
    /**
     * Checks if a given value represents a boolean.
     *
     * @param bool|int|float|string $value any scalar value.
     *
     * @return bool
     */
    public static function isBooleanRepresentative($value): bool
    {
        if (is_bool($value)) {
            return true;
        }

        if ($value === 1 || $value === 0) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $string = strtolower($value);
        return in_array($string, array("true", "false", "1", "0", "yes", "no"), true);
    }

    /**
     * Convert a string into its boolean value.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function stringToBoolean(string $value): bool
    {
        switch (strtolower($value)) {
            case 'true':
            case '1':
            case 'yes':
                return true;

            default:
                return false;
        }
    }
}
