<?php

namespace Icecave\Siphon\Result;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use IteratorAggregate;

/**
 * The response from a result feed.
 */
class ResultResponse implements ResponseInterface, Countable, IteratorAggregate
{
    use ResponseTrait;

    public function __construct(Sport $sport, Season $season)
    {
        $this->setSport($sport);
        $this->setSeason($season);
        $this->competitions = [];
    }

    /**
     * Get the requested sport.
     *
     * @return Sport The requested sport.
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Set the requested sport.
     *
     * @param Sport $sport The requested sport.
     */
    public function setSport(Sport $sport)
    {
        $this->sport = $sport;
    }

    /**
     * Get the requested season.
     *
     * @return Season The requested season.
     */
    public function season()
    {
        return $this->season;
    }

    /**
     * Set the requested season.
     *
     * @param Season $season The requested season.
     */
    public function setSeason(Season $season)
    {
        $this->season = $season;
    }

    /**
     * Check if the response contains seasons.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->competitions);
    }

    /**
     * Get the number of seasons in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->competitions);
    }

    /**
     * Iterate the seasons.
     *
     * @return mixed<Season>
     */
    public function getIterator()
    {
        foreach ($this->competitions as $competition) {
            yield $competition;
        }
    }

    /**
     * Add a competition to the response.
     *
     * @param CompetitionInterface $competition The competition to add.
     * @param boolean              $isFinalized True if finalized.
     */
    public function add(CompetitionInterface $competition, $isFinalized)
    {
        $this->competitions[$competition->id()] = [$competition, $isFinalized];
    }

    /**
     * Remove a competition from the response.
     *
     * @param CompetitionInterface $competition The competition to remove.
     */
    public function remove(CompetitionInterface $competition)
    {
        unset($this->competitions[$competition->id()]);
    }

    /**
     * Remove all seasons from the response.
     */
    public function clear()
    {
        $this->competitions = [];
    }

    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor)
    {
        return $visitor->visitResultResponse($this);
    }

    private $sport;
    private $season;
    private $competitions;
}
