<?php
namespace Icecave\Siphon\LiveScore;

use InvalidArgumentException;

interface LiveScoreInterface
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
     * Get the current scope.
     *
     * @return ScopeInterface|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope();

    /**
     * Add a scope.
     *
     * @param Scope $scope The scope to add.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function add(ScopeInterface $scope);

    /**
     * Get the scopes that make up the live score.
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
