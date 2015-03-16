<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Score\ScopeStatus;

trait LiveScoreTrait
{
    /**
     * Get the current scope.
     *
     * @return Scope|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope()
    {
        $scopes = $this->scopes();
        $scope  = end($scopes);

        if (!$scope) {
            return null;
        } elseif (ScopeStatus::COMPLETE() === $scope->status()) {
            return null;
        }

        return $scope;
    }

    /**
     * Get the scopes that make up the score.
     *
     * @return array<ScopeInterface>
     */
    abstract public function scopes();
}
