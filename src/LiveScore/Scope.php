<?php
namespace Icecave\Siphon\LiveScore;

class Scope
{
    public function __construct(
        ScopeType $type,
        ScopeStatus $status,
        $homePoints,
        $awayPoints
    ) {
        $this->type       = $type;
        $this->status     = $status;
        $this->homePoints = $homePoints;
        $this->awayPoints = $awayPoints;
    }

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
    public function homePoints()
    {
        return $this->homePoints;
    }

    /**
     * Get the home team's points for this scope.
     *
     * @param integer The away team's points.
     */
    public function awayPoints()
    {
        return $this->awayPoints;
    }

    private $type;
    private $status;
    private $homePoints;
    private $awayPoints;
}
