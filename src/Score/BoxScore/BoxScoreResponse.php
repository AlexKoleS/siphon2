<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;

/**
 * The response from a box score feed.
 */
class BoxScoreResponse implements ResponseInterface
{
    public function __construct(CompetitionInterface $competition)
    {
        $this->setCompetition($competition);
    }

    /**
     * Get the requested competition.
     *
     * @return CompetitionInterface
     */
    public function competition()
    {
        return $this->competition;
    }

    /**
     * Set the requested competition.
     *
     * @param CompetitionInterface $competition
     */
    public function setCompetition(CompetitionInterface $competition)
    {
        $this->competition = $competition;
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
        return $visitor->visitBoxScoreResponse($this);
    }

    private $competition;
}
