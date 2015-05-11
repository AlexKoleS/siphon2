<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;

/**
 * A season within a schedule.
 *
 * Seasons can be used as a collection of competitions, via
 * CompetitionCollectionInterface.
 *
 * @api
 */
interface SeasonInterface extends CompetitionCollectionInterface
{
    /**
     * Get the season ID.
     *
     * @return string The season ID.
     */
    public function id();

    /**
     * Get the season name.
     *
     * @return string The season name.
     */
    public function name();

    /**
     * Get the date on which the season starts.
     *
     * @return Date The start date of the season.
     */
    public function startDate();

    /**
     * Get the date on which the season ends.
     *
     * @return Date The end date of the season.
     */
    public function endDate();

    /**
     * Add a competition to the season.
     *
     * @param CompetitionInterface $competition The competition to add.
     */
    public function add(CompetitionInterface $competition);

    /**
     * Remove a competition from the season.
     *
     * @param CompetitionInterface $competition The competition to remove.
     */
    public function remove(CompetitionInterface $competition);
}
