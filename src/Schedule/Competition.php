<?php

namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;

/**
 * A sports competition (ie, "event" / "game" / "match" / etc).
 */
class Competition implements CompetitionInterface
{
    /**
     * @param string            $id        The competition ID.
     * @param CompetitionStatus $status    The status of the competition.
     * @param DateTime          $startTime The time at which the competition begins.
     * @param Sport             $sport     The sport (eg, baseball, football, etc).
     * @param Season            $season    The season in which the compeititon is played.
     * @param TeamInterface     $homeTeam  The home team.
     * @param TeamInterface     $awayTeam  The away team.
     */
    public function __construct(
        $id,
        CompetitionStatus $status,
        DateTime $startTime,
        Sport $sport,
        Season $season,
        TeamInterface $homeTeam,
        TeamInterface $awayTeam
    ) {
        $this->id             = $id;
        $this->status         = $status;
        $this->startTime      = $startTime;
        $this->sport          = $sport;
        $this->season         = $season;
        $this->homeTeam       = $homeTeam;
        $this->awayTeam       = $awayTeam;
        $this->notablePlayers = [];
    }

    /**
     * Get the competition ID.
     *
     * @return string The competition ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the competition status.
     *
     * @return CompetitionStatus The competition status.
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Get the time at which the competition begins.
     *
     * @return DateTime The competition start time.
     */
    public function startTime()
    {
        return $this->startTime;
    }

    /**
     * Get the sport.
     *
     * @return Sport The sport.
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Get the season.
     *
     * @return Season The season.
     */
    public function season()
    {
        return $this->season;
    }

    /**
     * Get the home team.
     *
     * @return TeamInterface The home team.
     */
    public function homeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Get the away team.
     *
     * @return TeamInterface The away team.
     */
    public function awayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Get any "notable" players for this competition.
     *
     * This includes:
     *  - MLB starting pitchers
     *
     * @return array<Player>
     */
    public function notablePlayers()
    {
        return array_values($this->notablePlayers);
    }

    /**
     * Add a notable player to the competition.
     *
     * @param Player $player The player to add.
     */
    public function addNotablePlayer(Player $player)
    {
        $this->notablePlayers[$player->id()] = $player;
    }

    /**
     * Remove a notable player from the competition.
     *
     * @param Player $player The player to remove.
     */
    public function removeNotablePlayer(Player $player)
    {
        unset($this->notablePlayers[$player->id()]);
    }

    private $id;
    private $status;
    private $startTime;
    private $sport;
    private $season;
    private $homeTeam;
    private $awayTeam;
    private $notablePlayers;
}
