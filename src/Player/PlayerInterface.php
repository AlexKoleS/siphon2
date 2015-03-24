<?php
namespace Icecave\Siphon\Player;

/**
 * A player, an athlete who participates in a competition.
 *
 * @api
 */
interface PlayerInterface
{
    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function id();

    /**
     * Get the player's first name.
     *
     * @return string The player's first name.
     */
    public function firstName();

    /**
     * Get the player's last name.
     *
     * @return string The player's last name.
     */
    public function lastName();

    /**
     * Get the player's display name.
     *
     * @return string The player's display name.
     */
    public function displayName();
}
