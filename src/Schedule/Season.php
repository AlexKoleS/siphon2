<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;

/**
 * A sporting season.
 */
class Season
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
     * @param Competition $competition The competition to add.
     */
    public function add(Competition $competition)
    {
        $this->competitions[] = $competition;
    }

    /**
     * Get the competitions in the season.
     *
     * @return array<Competition>
     */
    public function competitions()
    {
        return $this->competitions;
    }

    private $id;
    private $name;
    private $startDate;
    private $endDate;
    private $competitions;
}
