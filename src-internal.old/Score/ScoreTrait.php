<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

trait ScoreTrait
{
    /**
     * The home team's score.
     *
     * @return integer
     */
    public function homeTeamScore()
    {
        $score = 0;

        foreach ($this->scopes() as $scope) {
            $score += $scope->homeTeamScore();
        }

        return $score;
    }

    /**
     * The away team's score.
     *
     * @return integer
     */
    public function awayTeamScore()
    {
        $score = 0;

        foreach ($this->scopes() as $scope) {
            $score += $scope->awayTeamScore();
        }

        return $score;
    }

    /**
     * Add a scope to the score.
     *
     * @param ScopeInterface $scope The scope to add.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function add(ScopeInterface $scope)
    {
        $this->checkScopeType($scope);

        $this->scopes[] = $scope;
    }

    /**
     * Remove a scope from the score.
     *
     * @param ScopeInterface $scope The scope to remove.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function remove(ScopeInterface $scope)
    {
        $this->checkScopeType($scope);

        $index = array_search($scope, $this->scopes);

        if (false !== $index) {
            array_splice($this->scopes, $index, 1);
        }
    }

    /**
     * Get the scopes that make up the score.
     *
     * @return array<ScopeInterface>
     */
    public function scopes()
    {
        return $this->scopes;
    }

    abstract protected function checkScopeType(ScopeInterface $scope);

    private $scopes = [];
}
