<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderInterface;
use SimpleXMLElement;

/**
 * Client for reading schedule feeds.
 */
class ScheduleReader implements ScheduleReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a schedule feed.
     *
     * @param string $sport The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return Schedule
     */
    public function read($sport, $league)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/schedule/schedule_%s.xml',
                    $sport,
                    $league,
                    $league
                )
            )
            ->{'team-sport-content'}
            ->{'league-content'}
            ->{'season-content'};

        $schedule = new Schedule;

        foreach ($xml as $element) {
            $season = $this->createSeason($element->season);
            $schedule->add($season);

            foreach ($element->competition as $competitionElement) {
                $season->add(
                    $this->createCompetition(
                        $sport,
                        $league,
                        $competitionElement
                    )
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

    private function createCompetition(
        $sport,
        $league,
        SimpleXMLElement $element
    ) {
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
            $sport,
            $league,
            strval($element->{'home-team-content'}->{'team'}->{'id'}),
            strval($element->{'away-team-content'}->{'team'}->{'id'})
        );
    }

    private $xmlReader;
}
