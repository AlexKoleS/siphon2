<?php
namespace Icecave\Siphon\Schedule;

/**
 * A schedule containing zero or more seasons.
 *
 * @api
 */
interface ScheduleInterface extends CompetitionCollectionInterface
{
    /**
     * Add a season to the schedule.
     *
     * @param SeasonInterface $season The season to add.
     */
    public function add(SeasonInterface $season);

    /**
     * Remove a season from the schedule.
     *
     * @param SeasonInterface $season The season to remove.
     */
    public function remove(SeasonInterface $season);

    /**
     * Get the seasons in the schedule.
     *
     * @return array<SeasonInterface>
     */
    public function seasons();
}
