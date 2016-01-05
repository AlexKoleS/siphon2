<?php

namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Sport;
use SimpleXMLElement;

/**
 * A trait that provides features for constructing a competition from XML.
 */
trait CompetitionFactoryTrait
{
    private function createCompetition(
        SimpleXMLElement $element,
        Sport $sport,
        Season $season = null
    ) {
        $id = strval($element->id);

        $status = CompetitionStatus::memberByValue(
            strval($element->{'result-scope'}->{'competition-status'})
        );

        $startTime = DateTime::fromIsoString(
            $element->{'start-date'}
        );

        if ($element->{'result-scope'}->{'completed-date'}) {
            $endTime = DateTime::fromIsoString(
                $element->{'result-scope'}->{'completed-date'}
            );
        } else {
            $endTime = null;
        };

        $homeTeam = $this->createTeam(
            $element->{'home-team-content'}->team
        );

        $awayTeam = $this->createTeam(
            $element->{'away-team-content'}->team
        );

        if ($season) {
            return new Competition(
                $id,
                $status,
                $startTime,
                $endTime,
                $sport,
                $season,
                $homeTeam,
                $awayTeam
            );
        }

        return new CompetitionRef(
            $id,
            $status,
            $startTime,
            $sport,
            $homeTeam,
            $awayTeam
        );
    }

    abstract protected function createTeam(SimpleXMLElement $element);
}
