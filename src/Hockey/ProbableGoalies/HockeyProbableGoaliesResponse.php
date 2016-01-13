<?php

namespace Icecave\Siphon\Hockey\ProbableGoalies;

use Countable;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\Team;
use Icecave\Siphon\Team\TeamInterface;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * The response from a hockey probable goalies feed.
 */
class HockeyProbableGoaliesResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use ResponseTrait;

    public function __construct(Sport $sport)
    {
        $this->setSport($sport);
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
     * Check if the response contains competitions.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->competitions);
    }

    /**
     * Get the number of competitions in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->competitions);
    }

    /**
     * Iterate the competitions.
     *
     * @return mixed<array<CompetitionInterface,TeamInterface,Player>>
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
     * @param TeamInterface        $team        The team to add.
     * @param Player               $player      The player to add.
     */
    public function add(CompetitionInterface $competition, TeamInterface $team, Player $player)
    {
        if ($team === $competition->homeTeam()) {
            // same instance
        } elseif ($team === $competition->awayTeam()) {
            // same instance
        } else {
            throw new InvalidArgumentException(
                'The team object must be one of the ' . TeamInterface::class . ' instances from the competition object.'
            );
        }

        $key = sprintf('%s.%s.%s', $competition->id(), $team->id(), $player->id());

        $this->competitions[$key] = [$competition, $team, $player];
    }

    /**
     * Remove a competition from the response.
     *
     * @param CompetitionInterface $competition The competition to remove.
     * @param TeamInterface        $team        The team to remove.
     * @param Player               $player      The player to remove.
     */
    public function remove(CompetitionInterface $competition, TeamInterface $team, Player $player)
    {
        $key = sprintf('%s.%s.%s', $competition->id(), $team->id(), $player->id());

        unset($this->competitions[$key]);
    }

    /**
     * Remove all competitions from the response.
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
        return $visitor->visitHockeyProbableGoaliesResponse($this);
    }

    private $sport;
    private $competitions;
}
