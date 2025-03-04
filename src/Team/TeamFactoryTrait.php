<?php

namespace Icecave\Siphon\Team;

use Icecave\Siphon\Util\XPath;
use SimpleXMLElement;

/**
 * A trait that provides features for constructing a team from XML.
 */
trait TeamFactoryTrait
{
    private function createTeam(SimpleXMLElement $element)
    {
        $name         = XPath::stringOrNull($element, "name[@type='first']");
        $abbreviation = XPath::stringOrNull($element, "name[@type='short']");
        $nickname     = XPath::stringOrNull($element, "name[@type='nick']");

        if (null === $name || null === $abbreviation) {
            return new TeamRef(
                strval($element->id),
                XPath::string($element, 'name')
            );
        }

        return new Team(
            strval($element->id),
            $name,
            $abbreviation,
            $nickname
        );
    }
}
