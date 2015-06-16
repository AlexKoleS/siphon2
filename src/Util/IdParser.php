<?php
namespace Icecave\Siphon\Util;

use Icecave\Siphon\Sport;
use InvalidArgumentException;

/**
 * Internal utilities for manipulating entity IDs.
 */
abstract class IdParser
{
    public static function parse($id, Sport $sport, $type)
    {
        if (is_int($id) || ctype_digit($id)) {
            return intval($id);
        }

        $prefix  = '/sport/' . $sport->sport() . '/' . $type;
        $pattern = '/^' . preg_quote($prefix, '/') . ':(\d+)$/';

        $matches = [];
        if (preg_match($pattern, $id, $matches)) {
            return intval($matches[1]);
        }

        throw new InvalidArgumentException(
            sprintf(
                'Invalid ID: "%s", expected %s %s ID',
                $id,
                $sport->key(),
                $type
            )
        );
    }
}
