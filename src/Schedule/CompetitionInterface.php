<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\DateTime;

/**
 * A sports competition (ie, "event" / "game" / "match" / etc).
 *
 * @api
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
     * @return string The sport (eg, baseball, football, etc).
     */
    public function sport();

    /**
     * Get the league.
     *
     * @return string The league (eg, MLB, NFL, etc).
     */
    public function league();

    /**
     * Get the ID of the home team.
     *
     * @return string The home team ID.
     */
    public function homeTeamId();

    /**
     * Get the ID of the away team.
     *
     * @return string The away team ID.
     */
    public function awayTeamId();
}
