<?php

namespace Icecave\Siphon\Statistics;

use SimpleXMLElement;

/**
 * A trait that provides features for constructing statistics collection from XML.
 */
trait StatisticsFactoryTrait
{
    private function createStatisticsCollection(SimpleXMLElement $element)
    {
        $groups = [];

        foreach ($element->{'stat-group'} as $group) {
            $scopes = [];
            $stats  = [];

            foreach ($group->scope as $elem) {
                $type  = strval($elem['type']);
                $value = strval($elem['str']);

                $scopes[$type] = $value;
            }

            foreach ($group->stat as $elem) {
                $type  = strval($elem['type']);
                $value = strval($elem['num']);

                if (ctype_digit($value)) {
                    $stats[$type] = intval($value);
                } else {
                    $stats[$type] = floatval($value);
                }
            }

            $groups[] = new StatisticsGroup(
                strval($group->key),
                $scopes,
                $stats
            );
        }

        return new StatisticsCollection($groups);
    }
}
