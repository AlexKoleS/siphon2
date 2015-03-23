<?php
namespace Icecave\Siphon\Player;

/**
 * Encapsulates season-specific information about a player.
 *
 * @api
 */
interface InjuryInterface
{
    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId();

    /**
     * Get the season name.
     *
     * @return string The season name.
     */
    public function season();

    /**
     * Get the player's jersey number for this season.
     *
     * The result is provided as a string to allow numbers such as '00'.
     *
     * @return string The player's jersey number.
     */
    public function number();

    /**
     * Get the player's position for this season.
     *
     * This is the position "short name" or "code" such as "RP" for "Reliever".
     *
     * @return string The player's position.
     */
    public function position();

    /**
     * Get the player's position name.
     *
     * @return string The name of the player's position.
     */
    public function positionName();

    /**
     * Indicates whether or not the player is active on the team for this season.
     *
     * @return boolean
     */
    public function isActive();
}
