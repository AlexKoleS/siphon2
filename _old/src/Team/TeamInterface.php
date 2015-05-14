<?php
namespace Icecave\Siphon\Team;

/**
 * A team in a team sport.
 *
 * @api
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

    /**
     * Get the team nick name.
     *
     * @return string|null The team nick name (e.g. Cubs), or null if unavailable.
     */
    public function nickname();

    /**
     * Get the team short name.
     *
     * @return string The team short name (e.g. CHC).
     */
    public function abbreviation();

    /**
     * Get the team display name.
     *
     * @return string The team display name (e.g. Chicago Cubs).
     */
    public function displayName();
}
