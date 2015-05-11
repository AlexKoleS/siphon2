<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\ScopeInterface;
use Icecave\Siphon\Score\ScoreInterface;

/**
 * The result of reading a live score feed.
 *
 * @api
 */
interface LiveScoreResultInterface
{
    /**
     * Get the current scope, if any.
     *
     * @return ScopeInterface|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope();

    /**
     * Get the status of the current scope.
     *
     * @return ScopeStatus|null The status of the current scope, or null if the competition is not in-progress.
     */
    public function currentScopeStatus();

    /**
     * Get the current status of the competition.
     *
     * @return CompetitionStatus
     */
    public function competitionStatus();

    /**
     * Get the competition score.
     *
     * @return ScoreInterface
     */
    public function competitionScore();
}
