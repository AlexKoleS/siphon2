<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Player\StatisticsInterface;
use Icecave\Siphon\Score\ScoreInterface;

/**
 * The result of reading a box score feed.
 */
class Result implements BoxScoreResultInterface
{
    /**
     * Get per-player statistics for the competition.
     *
     * @return array<StatisticsInterface>
     */
    public function playerStatistics()
    {
        return $this->playerStatistics;
    }

    /**
     * Set per-player statistics for the competition.
     *
     * @return array<StatisticsInterface>
     */
    public function setPlayerStatistics(array $playerStatistics)
    {
        $this->playerStatistics = $playerStatistics;
    }

    /**
     * Get the competition score.
     *
     * @return ScoreInterface
     */
    public function competitionScore()
    {
        if (!$this->competitionScore) {
            throw new LogicException(
                'Score has not been set.'
            );
        }

        return $this->competitionScore;
    }

    /**
     * Set the competition score.
     *
     * @param ScoreInterface $score The competition score.
     */
    public function setCompetitionScore(ScoreInterface $score)
    {
        $this->competitionScore = $score;
    }

    private $playerStatistics = [];
    private $competitionScore;
}
