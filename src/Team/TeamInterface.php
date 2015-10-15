<?php

namespace Icecave\Siphon\Team;

/**
 * A team in a team sport.
 */
interface TeamInterface
{
    /**
     * Get the team ID.
     *
     * @return string The team ID.
     */
    public function id();

    /**
     * Get the team name.
     *
     * @return string The team name (e.g. Chicago).
     */
    public function name();
}
