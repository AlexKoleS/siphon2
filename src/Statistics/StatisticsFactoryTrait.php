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
        return new StatisticsCollection;
    }
}
