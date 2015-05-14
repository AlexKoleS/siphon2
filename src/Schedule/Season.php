<?php
namespace Icecave\Siphon\Schedule;

use Countable;
use Icecave\Chrono\Date;
use IteratorAggregate;

/**
 * A season within a schedule, a container for competitions.
 */
class Season implements
    SeasonInterface,
    Countable,
    IteratorAggregate
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
     * @return mixed<Competition>
     */
    public function getIterator()
    {
        foreach ($this->competitions as $competition) {
            yield $competition;
        }
    }

    /**
     * Add a competition to the season.
     *
     * @param Competition $competition The competition to add.
     */
    public function add(Competition $competition)
    {
        $this->competitions[$competition->id()] = $competition;
    }

    /**
     * Remove a competition from the season.
     *
     * @param Competition $competition The competition to remove.
     */
    public function remove(Competition $competition)
    {
        unset($this->competitions[$competition->id()]);
    }

    private $id;
    private $name;
    private $startDate;
    private $endDate;
    private $competitions;
}
