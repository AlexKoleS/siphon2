<?php
namespace Icecave\Siphon\Score;

/**
 * Score for innings-based sports.
 */
class InningsScore implements ScoreInterface, InningsStatisticsInterface
{
    use ScoreTrait;

    /**
     * Get the number of hits made by the home team.
     *
     * @return integer
     */
    public function homeTeamHits()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->homeTeamHits();
        }

        return $result;
    }

    /**
     * Get the number of hits made by the away team.
     *
     * @return integer
     */
    public function awayTeamHits()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->awayTeamHits();
        }

        return $result;
    }

    /**
     * Get the number of errors made by the home team.
     *
     * @return integer
     */
    public function homeTeamErrors()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->homeTeamErrors();
        }

        return $result;
    }

    /**
     * Get the number of errors made by the away team.
     *
     * @return integer
     */
    public function awayTeamErrors()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->awayTeamErrors();
        }

        return $result;
    }

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    public function scopeClass()
    {
        return Innings::class;
    }
}
