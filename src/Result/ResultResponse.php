<?php

namespace Icecave\Siphon\Result;

use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Reader\SportResponseInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;

/**
 * The response from a result feed.
 */
class ResultResponse implements SportResponseInterface
{
    use ResponseTrait;

    public function __construct(Sport $sport, Season $season)
    {
        $this->setSport($sport);
        $this->setSeason($season);
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
}
