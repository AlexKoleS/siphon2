<?php

namespace Icecave\Siphon\Player;

use Icecave\Siphon\Util\XPath;
use SimpleXMLElement;

/**
 * A trait that provides features for constructing a player from XML.
 */
trait PlayerFactoryTrait
{
    private function createPlayer(SimpleXMLElement $element)
    {
        $first = XPath::stringOrNull($element, "name[@type='first']");
        $last  = XPath::string($element, "name[@type='last']");

        if (null === $first) {
            $first = $last;
            $last  = null;
        }

        return new Player(
            strval($element->id),
            $first,
            $last
        );
    }
}
