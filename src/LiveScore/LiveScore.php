<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Chrono\TimeSpan\TimeSpanInterface;

class LiveScore
{
    /**
     * @param TimeSpanInterface $gameClock The game clock.
     */
    public function __construct(TimeSpanInterface $gameClock)
    {
        $this->gameClock = $gameClock;
        $this->scopes    = [];
    }

    /**
     * Get the current game clock time.
     *
     * @return TimeSpanInterface
     */
    public function gameClock()
    {
        return $this->gameClock;
    }

    /**
     * Get the current scope.
     *
     * @return Scope|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope()
    {
        $scope = end($this->scopes);

        if (!$scope) {
            return null;
        } elseif (ScopeStatus::COMPLETE() === $scope->status()) {
            return null;
        }

        return $scope;
    }

    /**
     * Add a scope.
     *
     * @param Scope $scope The scope to add.
     */
    public function add(Scope $scope)
    {
        $this->scopes[] = $scope;
    }

    /**
     * Get the scopes that make up the live score.
     *
     * @return array<Scope>
     */
    public function scopes()
    {
        return $this->scopes;
    }

    private $gameClock;
    private $scopes;
}
