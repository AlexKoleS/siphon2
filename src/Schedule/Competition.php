<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\DateTime;

/**
 * A sports competition (ie, "event" / "game" / "match" / etc).
 */
class Competition
{
    /**
     * @param string $id The competition ID.
     * @param CompetitionStatus $status The status of the competition.
     * @param DateTime $startTime The time at which the competition begins.
     * @param string $sport The sport (eg, baseball, football, etc).
     * @param string $league The league (eg, MLB, NFL, etc).
     * @param string $homeTeamId The ID of the home team.
     * @param string $awayTeamId The ID of the away team.
     */
    public function __construct(
        $id,
        CompetitionStatus $status,
        DateTime $startTime,
        $sport,
        $league,
        $homeTeamId,
        $awayTeamId
    ) {
        $this->id         = $id;
        $this->status     = $status;
        $this->startTime  = $startTime;
        $this->sport      = $sport;
        $this->league     = $league;
        $this->homeTeamId = $homeTeamId;
        $this->awayTeamId = $awayTeamId;
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
     * @return string The sport (eg, baseball, football, etc).
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Get the league.
     *
     * @return string The league (eg, MLB, NFL, etc).
     */
    public function league()
    {
        return $this->league;
    }

    /**
     * Get the ID of the home team.
     *
     * @return string The home team ID.
     */
    public function homeTeamId()
    {
        return $this->homeTeamId;
    }

    /**
     * Get the ID of the away team.
     *
     * @return string The away team ID.
     */
    public function awayTeamId()
    {
        return $this->awayTeamId;
    }

    private $id;
    private $status;
    private $startTime;
    private $sport;
    private $league;
    private $homeTeamId;
    private $awayTeamId;
}
