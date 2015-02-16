<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderInterface;
use SimpleXMLElement;

class ScheduleReader implements ScheduleReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    public function read($sport, $league)
    {
        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/schedule/schedule_%s.xml',
                    strtolower($sport),
                    strtoupper($league),
                    strtoupper($league)
                )
            )->xpath('team-sport-content/league-content/season-content');

        $schedule = new Schedule;

        foreach ($xml as $element) {
            $season = $this->createSeason($element->season);
            $schedule->add($season);

            foreach ($element->competition as $competitionElement) {
                $season->add(
                    $this->createCompetition($competitionElement)
                );
            }
        }

        return $schedule;
    }

    private function createSeason(SimpleXMLElement $element)
    {
        $startDate = Date::fromIsoString(
            $element->{'details'}->{'start-date'}
        );

        $endDate = Date::fromIsoString(
            $element->{'details'}->{'end-date'}
        );

        return new Season(
            strval($element->id),
            strval($element->name),
            $startDate,
            $endDate
        );
    }

    private function createCompetition(SimpleXMLElement $element)
    {
        $status = CompetitionStatus::memberByValue(
            strval($element->{'result-scope'}->{'competition-status'})
        );

        $startTime = DateTime::fromIsoString(
            $element->{'start-date'}
        );

        return new Competition(
            strval($element->id),
            $status,
            $startTime,
            strval($element->{'home-team-content'}->{'team'}->{'id'}),
            strval($element->{'away-team-content'}->{'team'}->{'id'})
        );
    }

    private $xmlReader;
}
