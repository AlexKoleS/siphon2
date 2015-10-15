<?php

namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use SimpleXMLElement;

/**
 * A trait that provides features for constructing a season from XML.
 */
trait SeasonFactoryTrait
{
    private function createSeason(SimpleXMLElement $element)
    {
        return new Season(
            strval($element->id),
            strval($element->name),
            Date::fromIsoString(
                $element->details->{'start-date'}
            ),
            Date::fromIsoString(
                $element->details->{'end-date'}
            )
        );
    }
}
