<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Score\ScopeInterface;
use Icecave\Siphon\Score\ScoreInterface;

interface LiveScoreInterface extends ScoreInterface
{
    /**
     * Get the current scope.
     *
     * @return ScopeInterface|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope();
}
