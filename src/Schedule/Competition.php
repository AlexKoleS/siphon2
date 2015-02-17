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
     * @param string $homeTeamId The ID of the home team.
     * @param string $awayTeamId The ID of the away team.
     */
    public function __construct(
        $id,
        CompetitionStatus $status,
        DateTime $startTime,
        $homeTeamId,
        $awayTeamId
    ) {
        $this->id         = $id;
        $this->status     = $status;
        $this->startTime  = $startTime;
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
    private $homeTeamId;
    private $awayTeamId;
}
