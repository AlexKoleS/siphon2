<?php
namespace Icecave\Siphon\Player;

/**
 * A player, an athlete who participates in a competition.
 */
class Player
{
    /**
     * @param string      $id
     * @param string      $firstName
     * @param string|null $lastName
     */
    public function __construct(
        $id,
        $firstName,
        $lastName
    ) {
        $this->id        = $id;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the player's first name.
     *
     * @return string The player's first name.
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * Get the player's last name.
     *
     * @return string|null The player's last name, or null if the player only has a single name.
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * Get the player's display name.
     *
     * @return string The player's display name.
     */
    public function displayName()
    {
        return trim(
            sprintf(
                '%s %s',
                $this->firstName,
                $this->lastName
            )
        );
    }

    private $id;
    private $firstName;
    private $lastName;
}
