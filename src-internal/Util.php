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
}
