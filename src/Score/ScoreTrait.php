<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

trait ScoreTrait
{
    /**
     * The home team's total score.
     *
     * @return integer
     */
    public function homeTeamScore()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->homeTeamPoints();
        }

        return $result;
    }

    /**
     * The away team's total score.
     *
     * @return integer
     */
    public function awayTeamScore()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->awayTeamPoints();
        }

        return $result;
    }

    /**
     * Add a scope.
     *
     * @param Scope $scope The scope to add.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function add(ScopeInterface $scope)
    {
        $className = $this->scopeClass();

        if (!$scope instanceof $className) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unexpected scope type "%s", expected "%s".',
                    get_class($scope),
                    $className
                )
            );
        }

        $this->scopes[] = $scope;
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

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    abstract public function scopeClass();

    private $scopes = [];
}
