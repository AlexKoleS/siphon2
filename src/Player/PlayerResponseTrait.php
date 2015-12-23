<?php

namespace Icecave\Siphon\Player;

use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;

/**
 * Common implementation for response that operate per sport + season + team.
 */
trait PlayerResponseTrait
{
    public function __construct(
        Sport $sport,
        Season $season,
        TeamInterface $team
    ) {
        $this->setSport($sport);
        $this->setSeason($season);
        $this->setTeam($team);

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
     * Get the requested team.
     *
     * @return TeamInterface The requested team.
     */
    public function team()
    {
        return $this->team;
    }

    /**
     * Set the requested team.
     *
     * @param TeamInterface $team The requested team.
     */
    public function setTeam(TeamInterface $team)
    {
        $this->team = $team;
    }

    /**
     * Check if the response contains players.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->entries);
    }

    /**
     * Get the number of players in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Iterate the players.
     *
     * @return mixed<tuple<Player, mixed>>
     */
    public function getIterator()
    {
        foreach ($this->entries as $entry) {
            yield $entry;
        }
    }

    /**
     * Remove a player from the response.
     *
     * @param Player $player The player to remove.
     */
    public function remove(Player $player)
    {
        unset($this->entries[$player->id()]);
    }

    /**
     * Remove all players from the response.
     */
    public function clear()
    {
        $this->entries = [];
    }

    private $sport;
    private $team;
    private $entries;
}
