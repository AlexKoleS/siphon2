<?php
namespace Icecave\Siphon\Team;

/**
 * A reference to a team.
 *
 * A reference does not make the full data of the team available.
 */
class TeamRef implements TeamInterface
{
    /**
     * @param string $id   The team ID.
     * @param string $name The team name.
     */
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
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

    private $id;
    private $name;
}
