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
     * Get the current scope, if one is in-progress.
     *
     * @return ScopeInterface|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope();

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
