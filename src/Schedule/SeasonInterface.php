<?php
namespace Icecave\Siphon\Schedule;

/**
 * An interface for seasons an season references.
 */
interface SeasonInterface
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
}
