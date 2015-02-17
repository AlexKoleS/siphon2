<?php
namespace Icecave\Siphon\LiveScore;

class Scope
{
    /**
     * Get the type of the scope.
     *
     * @return ScopeType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Get the status of the scope.
     *
     * @return ScopeStatus
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Get the home team's points for this scope.
     *
     * @param integer The home team's points.
     */
    public function home()
    {
        return $this->home;
    }

    /**
     * Get the home team's points for this scope.
     *
     * @param integer The away team's points.
     */
    public function away()
    {
        return $this->away;
    }
}
