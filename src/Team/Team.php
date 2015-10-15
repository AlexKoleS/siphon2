<?php

namespace Icecave\Siphon\Team;

/**
 * A sports team.
 */
class Team implements TeamInterface
{
    /**
     * @param string      $id
     * @param string      $name
     * @param string      $abbreviation
     * @param string|null $nickname
     */
    public function __construct(
        $id,
        $name,
        $abbreviation,
        $nickname = null
    ) {
        $this->id           = $id;
        $this->name         = $name;
        $this->abbreviation = $abbreviation;
        $this->nickname     = $nickname;
    }

    /**
     * Get the team ID.
     *
     * @return string The team ID.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Get the team name.
     *
     * @return string The team name (e.g. Chicago).
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Get the team nick name.
     *
     * @return string|null The team nick name (e.g. Cubs), or null if unavailable.
     */
    public function nickname()
    {
        return $this->nickname;
    }

    /**
     * Get the team short name.
     *
     * @return string The team short name (e.g. CHC).
     */
    public function abbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Get the team display name.
     *
     * @return string The team display name (e.g. Chicago Cubs).
     */
    public function displayName()
    {
        if (null === $this->nickname) {
            return $this->name;
        }

        return $this->name . ' ' . $this->nickname;
    }

    private $id;
    private $name;
    private $nickname;
    private $abbreviation;
}
