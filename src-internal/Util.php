<?php
namespace Icecave\Siphon;

use InvalidArgumentException;

abstract class Util
{
    public static function extractNumericId($id)
    {
        $matches = [];

        if (preg_match('/:(\d+)$/', $id, $matches)) {
            return intval($matches[1]);
        }

        throw new InvalidArgumentException(
            'Invalid identifier.'
        );
    }
}
