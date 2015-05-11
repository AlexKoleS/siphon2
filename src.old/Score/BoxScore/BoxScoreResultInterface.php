<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\ScoreInterface;

/**
 * The result of reading a box score feed.
 *
 * @api
 */
interface BoxScoreResultInterface
{
    /**
     * Get per-player statistics for the competition.
     *
     * @return array<StatisticsInterface>
     */
    public function playerStatistics();

    /**
     * Get the competition score.
     *
     * @return ScoreInterface
     */
    public function competitionScore();
}
