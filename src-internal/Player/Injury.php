<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;

/**
 * Information about a specific injury.
 */
class Injury implements InjuryInterface
{
    public function __construct(
        $playerId,
        $type,
        InjuryStatus $status,
        $statusInformation,
        $note,
        Date $date,
        DateTime $updatedTime
    ) {
        $this->playerId          = $playerId;
        $this->type              = $type;
        $this->status            = $status;
        $this->statusInformation = $statusInformation;
        $this->note              = $note;
        $this->date              = $date;
        $this->updatedTime       = $updatedTime;
    }

    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId()
    {
        return $this->playerId;
    }

    /**
     * The type of injury.
     *
     * This may be a body location of a very brief description of the injury.
     *
     * @return string The injury type.
     */
    public function type()
    {
        return $this->type;
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
     * Get a human-readable note about the injury.
     *
     * @return string The note.
     */
    public function note()
    {
        return $this->note;
    }

    /**
     * The date when the injury was recorded.
     *
     * @return Date The injury start date.
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * The last time at which information about this player's injury was updated.
     *
     * @return DateTime The last update time.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    private $playerId;
    private $type;
    private $status;
    private $statusInformation;
    private $note;
    private $date;
    private $updatedTime;
}
