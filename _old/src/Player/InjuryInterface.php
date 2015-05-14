<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;

/**
 * Information about a specific injury.
 *
 * @api
 */
interface InjuryInterface
{
    /**
     * Get the injury ID.
     *
     * @return string The injury ID.
     */
    public function id();

    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId();

    /**
     * The type of injury.
     *
     * This may be a body location of a very brief description of the injury.
     *
     * @return string The injury type.
     */
    public function type();

    /**
     * Get the injury status.
     *
     * @return InjuryStatus
     */
    public function status();

    /**
     * Get a very short human-readable description of the status.
     *
     * @return string The injury display status.
     */
    public function statusInformation();

    /**
     * Get a human-readable note about the injury.
     *
     * @return string The note.
     */
    public function note();

    /**
     * The date when the injury was recorded.
     *
     * @return Date The injury start date.
     */
    public function date();

    /**
     * The last time at which information about this player's injury was updated.
     *
     * @return DateTime|null The last update time, or null if the update time is unknown.
     */
    public function updatedTime();
}
