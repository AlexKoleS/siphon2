<?php
namespace Icecave\Siphon\Team;

/**
 * Represents a team in a team sport.
 */
class Team
{
    public function __construct(
        $id,
        $name,
        $nickname,
        $abbreviation
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->nickname  = $nickname;
        $this->abbreviation = $abbreviation;
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
     * @return string The team nick name (e.g. Cubs).
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
        return $this->name . ' ' . $this->nickname;
    }

    private $id;
    private $name;
    private $nickname;
    private $abbreviation;
}
