<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Util;
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
                Util::extractStatisticsGroups($element)
            );
        }

        return $result;
    }
}
