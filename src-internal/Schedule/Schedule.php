<?php
namespace Icecave\Siphon\Schedule;

/**
 * A sporting schedule.
 */
class Schedule implements ScheduleInterface
{
    /**
     * Add a season to the schedule.
     *
     * @param SeasonInterface $season The season to add.
     */
    public function add(SeasonInterface $season)
    {
        $this->seasons[] = $season;
    }

    /**
     * Remove a season from the schedule.
     *
     * @param SeasonInterface $season The season to remove.
     */
    public function remove(SeasonInterface $season)
    {
        $index = array_search($season, $this->seasons);

        if (false !== $index) {
            array_splice($this->seasons, $index, 1);
        }
    }

    /**
     * Get the seasons in the schedule.
     *
     * @return array<SeasonInterface>
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
        return iterator_to_array($this);
    }

    /**
     * Indicates whether or not the collection contains any competitions.
     *
     * @return boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty()
    {
        foreach ($this->seasons as $season) {
            if (!$season->isEmpty()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the number of competitions in the collection.
     *
     * @return integer The number of competitions in the collection.
     */
    public function count()
    {
        $count = 0;

        foreach ($this->seasons as $season) {
            $count += $season->count();
        }

        return $count;
    }

    /**
     * Iterate over the competitions.
     *
     * @return mixed<CompetitionInterface>
     */
    public function getIterator()
    {
        foreach ($this->seasons as $season) {
            foreach ($season->competitions() as $competition) {
                yield $competition;
            }
        }
    }

    private $seasons = [];
}
