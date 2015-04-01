<?php
namespace Icecave\Siphon;

use InvalidArgumentException;
use SimpleXMLElement;

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

    public static function extractStatisticsGroups(SimpleXMLElement $element)
    {
        $result = [];

        foreach ($element->{'stat-group'} as $group) {
            $stats = [];

            foreach ($group->stat as $stat) {
                $key   = strval($stat['type']);
                $value = strval($stat['num']);

                if (ctype_digit($value)) {
                    $stats[$key] = intval($value);
                } else {
                    $stats[$key] = floatval($value);
                }
            }

            if ($stats) {
                $result[strval($group->key)] = $stats;
            }
        }

        return $result;
    }

    /**
     * A sscanf()-like parse function.
     *
     * @param string $pattern An sscanf-style pattern to match.
     * @param string $string  The string to match.
     */
    public static function parse($pattern, $string)
    {
        // Convert a sprintf-style pattern to a regex. We can't use built-in
        // sscanf() because the placeholders are not separated by whitespace.
        $pattern = preg_replace_callback(
            '/(?<!%)%[sd]/',
            function ($matches) {
                if ($matches[0] === '%d') {
                    return '(\d+)';
                }

                return '(.+)';
            },
            preg_quote($pattern, '/')
        );

        $matches = [];

        if (preg_match('/^' . $pattern . '$/', $string, $matches)) {
            return array_slice($matches, 1);
        }

        return null;
    }
}
