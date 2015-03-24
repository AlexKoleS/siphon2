<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\XPath;
use SimpleXMLElement;

/**
 * Create player and statistics objects from player content.
 */
class StatisticsFactory
{
    public function create(SimpleXMLElement $xml)
    {
        $season = XPath::string($xml, '//season/name');
        $result = [];

        foreach ($xml->xpath('//player-content') as $element) {
            $result[] = new Statistics(
                strval($element->{'player'}->id),
                $season,
                $this->aggregateStatistics($element)
            );
        }

        return $result;
    }

    private function aggregateStatistics(SimpleXMLElement $element)
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
