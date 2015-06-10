<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;

/**
 * A sports competition (ie, "event" / "game" / "match" / etc).
 */
interface CompetitionInterface
{
    /**
     * Get the competition ID.
     *
     * @return string The competition ID.
     */
    public function id();

    /**
     * Get the competition status.
     *
     * @return CompetitionStatus The competition status.
     */
    public function status();

    /**
     * Get the time at which the competition begins.
     *
     * @return DateTime The competition start time.
     */
    public function startTime();

    /**
     * Get the sport.
     *
     * @return Sport The sport.
     */
    public function sport();

    /**
     * Get the home team.
     *
     * @return TeamInterface The home team.
     */
    public function homeTeam();

    /**
     * Get the away team.
     *
     * @return TeamInterface The away team.
     */
    public function awayTeam();
}
