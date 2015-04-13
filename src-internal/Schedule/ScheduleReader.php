<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;
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
     * @param string             $sport  The sport (eg, baseball, football, etc)
     * @param string             $league The league (eg, MLB, NFL, etc)
     * @param ScheduleLimit|null $limit  Limit results to a competitions within a certain timeframe.
     *
     * @return ScheduleInterface
     */
    public function read($sport, $league, ScheduleLimit $limit = null)
    {
        if (null === $limit) {
            $limit = ScheduleLimit::NONE();
        }

        $sport    = strtolower($sport);
        $league   = strtoupper($league);

        if (ScheduleLimit::NONE() === $limit) {
            $resource = sprintf(
                self::URL_PATTERN,
                $sport,
                $league,
                $league
            );
        } else {
            $resource = sprintf(
                self::URL_PATTERN_LIMITED,
                $sport,
                $league,
                $league,
                $limit->value()
            );
        }

        return $this->readResource($sport, $league, $resource);
    }

    /**
     * Read the deleted schedule feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return ScheduleInterface
     */
    public function readDeleted($sport, $league)
    {
        $sport    = strtolower($sport);
        $league   = strtoupper($league);
        $resource = sprintf(
            self::URL_PATTERN_DELETED,
            $sport,
            $league,
            $league
        );

        return $this->readResource($sport, $league, $resource);
    }

    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return mixed
     */
    public function readAtomEntry(AtomEntry $atomEntry)
    {
        $parameters = [];

        if (!$this->supportsAtomEntry($atomEntry, $parameters)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        if ($parameters['deleted']) {
            return $this->readDeleted(
                $parameters['sport'],
                $parameters['league']
            );
        }

        return $this->read(
            $parameters['sport'],
            $parameters['league'],
            $parameters['limit']
        );
    }

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry   The atom entry.
     * @param array     &$parameters Populated with reader-specific parameters represented by the atom entry.
     *
     * @return boolean
     */
    public function supportsAtomEntry(
        AtomEntry $atomEntry,
        array &$parameters = []
    ) {
        if ($atomEntry->parameters()) {
            return false;
        }

        $matches = Util::parse(
            self::URL_PATTERN_LIMITED,
            $atomEntry->resource()
        );

        if (null !== $matches) {
            $parameters = [
                'sport'   => $matches[0],
                'league'  => $matches[1],
                'limit'   => ScheduleLimit::memberByValue(intval($matches[3])),
                'deleted' => false,
            ];

            return true;
        }

        $matches = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        if (null !== $matches) {
            $parameters = [
                'sport'   => $matches[0],
                'league'  => $matches[1],
                'limit'   => ScheduleLimit::NONE(),
                'deleted' => false,
            ];

            return true;
        }

        $matches = Util::parse(
            self::URL_PATTERN_DELETED,
            $atomEntry->resource()
        );

        if (null !== $matches) {
            $parameters = [
                'sport'   => $matches[0],
                'league'  => $matches[1],
                'limit'   => null,
                'deleted' => true,
            ];

            return true;
        }

        return false;
    }

    private function readResource($sport, $league, $resource)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read($resource)
            ->xpath('//season-content');

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
            $element->details->{'start-date'}
        );

        $endDate = Date::fromIsoString(
            $element->details->{'end-date'}
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

    const URL_PATTERN         = '/sport/v2/%s/%s/schedule/schedule_%s.xml';
    const URL_PATTERN_LIMITED = '/sport/v2/%s/%s/schedule/schedule_%s_%d_days.xml';
    const URL_PATTERN_DELETED = '/sport/v2/%s/%s/games-deleted/games_deleted_%s.xml';

    private $xmlReader;
}
