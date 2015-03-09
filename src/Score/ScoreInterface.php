<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

interface ScoreInterface
{
    /**
     * The home team's total score.
     *
     * @return integer
     */
    public function homeTeamScore();

    /**
     * The away team's total score.
     *
     * @return integer
     */
    public function awayTeamScore();

    /**
     * Add a scope.
     *
     * @param Scope $scope The scope to add.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function add(ScopeInterface $scope);

    /**
     * Get the scopes that make up the score.
     *
     * @return array<ScopeInterface>
     */
    public function scopes();

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    public function scopeClass();
}
