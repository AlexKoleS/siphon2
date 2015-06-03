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
        $abbreviation = XPath::stringOrNull($element, "name[@type='short']");

        if (null === $abbreviation) {
            return new TeamRef(
                strval($element->id),
                XPath::string($element, 'name')
            );
        }

        return new Team(
            strval($element->id),
            XPath::string($element, "name[@type='first']"),
            $abbreviation,
            XPath::stringOrNull($element, "name[@type='nick']")
        );
    }
}
