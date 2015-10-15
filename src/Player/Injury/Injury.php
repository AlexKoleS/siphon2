<?php

namespace Icecave\Siphon\Player\Injury;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;

/**
 * Information about a specific injury.
 */
class Injury
{
    public function __construct(
        $id,
        $location,
        InjuryStatus $status,
        $statusInformation,
        $statusNote,
        Date $startDate,
        DateTime $updatedTime = null
    ) {
        $this->id                = $id;
        $this->location          = $location;
        $this->status            = $status;
        $this->statusInformation = $statusInformation;
        $this->statusNote        = $statusNote;
        $this->startDate         = $startDate;
        $this->updatedTime       = $updatedTime;
    }

    /**
     * Get the injury ID.
     *
     * @return string The injury ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the location of the injury.
     *
     * @return string The injury location.
     */
    public function location()
    {
        return $this->location;
    }

    /**
     * Get the injury status.
     *
     * @return InjuryStatus
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Get a very short human-readable description of the status.
     *
     * @return string The injury display status.
     */
    public function statusInformation()
    {
        return $this->statusInformation;
    }

    /**
     * Get a human-readable note about the injury status.
     *
     * @return string The note.
     */
    public function statusNote()
    {
        return $this->statusNote;
    }

    /**
     * The date when the injury was recorded.
     *
     * @return Date The injury start date.
     */
    public function startDate()
    {
        return $this->startDate;
    }

    /**
     * The last time at which information about this player's injury was updated.
     *
     * @return DateTime|null The last update time, or null if the update time is unknown.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    private $id;
    private $location;
    private $status;
    private $statusInformation;
    private $statusNote;
    private $startDate;
    private $updatedTime;
}
