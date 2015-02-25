<?php
namespace Icecave\Siphon\LiveScore;

use SimpleXMLElement;

interface StatisticsAggregatorInterface
{
    /**
     * Extract statistics from the given live score XML.
     *
     * @param SimpleXMLElement $xml
     *
     * @return tuple<array, array> A 2-tuple of statistics for the home team and away team.
     */
    public function extract(SimpleXMLElement $xml);
}
