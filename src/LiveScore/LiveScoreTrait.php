<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Score\ScopeStatus;
use Icecave\Siphon\Score\ScoreTrait;

trait LiveScoreTrait
{
    use ScoreTrait;

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
}
