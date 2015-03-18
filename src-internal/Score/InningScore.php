<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

class InningScore implements InningScoreInterface
{
    use ScoreTrait;

    /**
     * Get the number of hits made by the home team.
     *
     * @return integer
     */
    public function homeTeamHits()
    {
        $total = 0;

        foreach ($this->scopes() as $scope) {
            $total += $scope->homeTeamHits();
        }

        return $total;
    }

    /**
     * Get the number of hits made by the away team.
     *
     * @return integer
     */
    public function awayTeamHits()
    {
        $total = 0;

        foreach ($this->scopes() as $scope) {
            $total += $scope->awayTeamHits();
        }

        return $total;
    }

    /**
     * Get the number of errors made by the home team.
     *
     * @return integer
     */
    public function homeTeamErrors()
    {
        $total = 0;

        foreach ($this->scopes() as $scope) {
            $total += $scope->homeTeamErrors();
        }

        return $total;
    }

    /**
     * Get the number of errors made by the away team.
     *
     * @return integer
     */
    public function awayTeamErrors()
    {
        $total = 0;

        foreach ($this->scopes() as $scope) {
            $total += $scope->awayTeamErrors();
        }

        return $total;
    }

    private function checkScopeType(ScopeInterface $scope)
    {
        if (!$scope instanceof InningInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported scope type "%s", expected "%s".',
                    get_class($scope),
                     InningInterface::class
                )
            );
        }
    }
}
