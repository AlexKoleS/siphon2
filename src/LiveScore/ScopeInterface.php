<?php
namespace Icecave\Siphon\LiveScore;

interface ScopeInterface
{
    /**
     * Get the status of the scope.
     *
     * @return ScopeStatus
     */
    public function status();

    /**
     * Set the status of the scope.
     *
     * @param ScopeStatus $status
     */
    public function setStatus(ScopeStatus $status);

    /**
     * The "points" earned by the home team.
     *
     * @return integer
     */
    public function homeTeamPoints();

    /**
     * The "points" earned by the away team.
     *
     * @return integer
     */
    public function awayTeamPoints();
}
