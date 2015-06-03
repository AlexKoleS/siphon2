<?php
namespace Icecave\Siphon\Team;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use IteratorAggregate;

/**
 * The response from a team feed.
 */
class TeamResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    public function __construct(Sport $sport, Season $season)
    {
        $this->setSport($sport);
        $this->setSeason($season);
        $this->teams = [];
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
     * Check if the response contains teams.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->teams);
    }

    /**
     * Get the number of teams in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->teams);
    }

    /**
     * Iterate the teams.
     *
     * @return mixed<TeamInterface>
     */
    public function getIterator()
    {
        foreach ($this->teams as $team) {
            yield $team;
        }
    }

    /**
     * Add a team to the response.
     *
     * @param TeamInterface $team The team to add.
     */
    public function add(TeamInterface $team)
    {
        $this->teams[$team->id()] = $team;
    }

    /**
     * Remove a team from the response.
     *
     * @param TeamInterface $team The team to remove.
     */
    public function remove(TeamInterface $team)
    {
        unset($this->teams[$team->id()]);
    }

    /**
     * Remove all teams from the response.
     */
    public function clear()
    {
        $this->teams = [];
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
        return $visitor->visitTeamResponse($this);
    }

    private $sport;
    private $type;
    private $teams;
}
