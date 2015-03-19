<?php
namespace Icecave\Siphon\Player;

/**
 * Encapsulates season-specific information about a player.
 */
class PlayerSeasonDetails implements PlayerSeasonDetailsInterface
{
    /**
     * @param string      $playerId
     * @param string      $season
     * @param string|null $number
     * @param string      $position
     * @param string      $positionName
     * @param boolean     $isActive
     */
    public function __construct(
        $playerId,
        $season,
        $number,
        $position,
        $positionName,
        $isActive
    ) {
        $this->playerId     = $playerId;
        $this->season       = $season;
        $this->number       = $number;
        $this->position     = $position;
        $this->positionName = $positionName;
        $this->isActive     = $isActive;
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
     * Get the season name.
     *
     * @return string The season name.
     */
    public function season()
    {
        return $this->season;
    }

    /**
     * Get the player's jersey number for this season.
     *
     * The result is provided as a string to allow numbers such as '00'.
     *
     * @return string|null The player's jersey number, or null if it is unknown.
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * Get the player's position for this season.
     *
     * This is the position "short name" or "code" such as "RP" for "Reliever".
     *
     * @return string The player's position.
     */
    public function position()
    {
        return $this->position;
    }

    /**
     * Get the player's position name.
     *
     * @return string The name of the player's position.
     */
    public function positionName()
    {
        return $this->positionName;
    }

    /**
     * Indicates whether or not the player is active on the team for this season.
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }

    private $playerId;
    private $season;
    private $number;
    private $position;
    private $positionName;
    private $isActive;
}
