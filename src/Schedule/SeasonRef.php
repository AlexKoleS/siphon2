<?php
namespace Icecave\Siphon\Schedule;

/**
 * A reference to a season within a schedule.
 *
 * A reference does not make the full data of the season available.
 */
class SeasonRef implements SeasonInterface
{
    /**
     * @param string $id   The season ID.
     * @param string $name The season name.
     */
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
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

    private $id;
    private $name;
}
