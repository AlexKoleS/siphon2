<?php

namespace Icecave\Siphon\Team;

use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;

/**
 * Common implementation for response that operate per sport + season + team.
 */
trait TeamResponseTrait
{
    use ResponseTrait;

    public function __construct(
        Sport $sport,
        Season $season
    ) {
        $this->setSport($sport);
        $this->setSeason($season);

        $this->entries = [];
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
        return empty($this->entries);
    }

    /**
     * Get the number of teams in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Iterate the teams.
     *
     * @return mixed<TeamInterface>
     */
    public function getIterator()
    {
        foreach ($this->entries as $team) {
            yield $team;
        }
    }

    /**
     * Remove a team from the response.
     *
     * @param TeamInterface $team The team to remove.
     */
    public function remove(TeamInterface $team)
    {
        unset($this->entries[$team->id()]);
    }

    /**
     * Remove all teams from the response.
     */
    public function clear()
    {
        $this->entries = [];
    }

    private $sport;
    private $entries;
}
