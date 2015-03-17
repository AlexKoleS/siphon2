<?php
namespace Icecave\Siphon\Schedule;

use ArrayIterator;
use Icecave\Chrono\Date;

/**
 * A season within a schedule.
 */
class Season implements SeasonInterface
{
    /**
     * @param string $id        The season ID.
     * @param string $name      The season name.
     * @param Date   $startDate The start date of the season.
     * @param Date   $endDate   The end date of the season.
     */
    public function __construct(
        $id,
        $name,
        Date $startDate,
        Date $endDate
    ) {
        $this->id           = $id;
        $this->name         = $name;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
        $this->competitions = [];
    }

    /**
     * Get the season ID.
     *
     * @return string The season ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the season name.
     *
     * @return string The season name.
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get the date on which the season starts.
     *
     * @return Date The start date of the season.
     */
    public function startDate()
    {
        return $this->startDate;
    }

    /**
     * Get the date on which the season ends.
     *
     * @return Date The end date of the season.
     */
    public function endDate()
    {
        return $this->endDate;
    }

    /**
     * Add a competition to the season.
     *
     * @param CompetitionInterface $competition The competition to add.
     */
    public function add(CompetitionInterface $competition)
    {
        $this->competitions[] = $competition;
    }

    /**
     * Add a competition to the season.
     *
     * @param CompetitionInterface $competition The competition to add.
     */
    public function remove(CompetitionInterface $competition)
    {
        $index = array_search($competition, $this->competitions);

        if (false !== $index) {
            array_splice($this->competitions, $index, 1);
        }
    }

    /**
     * Get the competitions in the collection.
     *
     * @return array<CompetitionInterface>
     */
    public function competitions()
    {
        return $this->competitions;
    }

    /**
     * Indicates whether or not the collection contains any competitions.
     *
     * @return boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->competitions);
    }

    /**
     * Get the number of competitions in the collection.
     *
     * @return integer The number of competitions in the collection.
     */
    public function count()
    {
        return count($this->competitions);
    }

    /**
     * Iterate over the competitions.
     *
     * @return mixed<CompetitionInterface>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->competitions);
    }

    private $id;
    private $name;
    private $startDate;
    private $endDate;
    private $competitions;
}
