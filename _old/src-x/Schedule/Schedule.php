<?php
namespace Icecave\Siphon\Schedule;

/**
 * A sporting schedule.
 */
class Schedule
{
    /**
     * Add a season to the schedule.
     *
     * @param Season $season The season to add.
     */
    public function add(Season $season)
    {
        $this->seasons[] = $season;
    }

    /**
     * Get the seasons in the schedule.
     *
     * @return array<Season>
     */
    public function seasons()
    {
        return $this->seasons;
    }

    /**
     * Get the competitions in the schedule.
     *
     * @return array<Competition>
     */
    public function competitions()
    {
        $competitions = [];

        foreach ($this->seasons as $season) {
            foreach ($season->competitions() as $competition) {
                $competitions[] = $competition;
            }
        }

        return $competitions;
    }

    private $seasons = [];
}
