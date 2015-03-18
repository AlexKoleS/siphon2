<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\ScopeInterface;
use Icecave\Siphon\Score\ScoreInterface;
use LogicException;

trait ResultTrait
{
    /**
     * Get the current scope, if one is in-progress.
     *
     * @return ScopeInterface|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope()
    {
        return $this->currentScope;
    }

    /**
     * Set the current scope.
     *
     * @param ScopeInterface|null $scope
     */
    public function setCurrentScope(ScopeInterface $scope = null)
    {
        $this->currentScope = $scope;
    }

    /**
     * Get the current status of the competition.
     *
     * @return CompetitionStatus
     */
    public function competitionStatus()
    {
        if (null === $this->competitionStatus) {
            return CompetitionStatus::OTHER();
        }

        return $this->competitionStatus;
    }

    /**
     * Set the current status of the competition.
     *
     * @param CompetitionStatus $status
     */
    public function setCompetitionStatus(CompetitionStatus $status)
    {
        $this->competitionStatus = $status;
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

    private $currentScope;
    private $competitionStatus;
    private $competitionScore;
}
