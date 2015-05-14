<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

/**
 * A competition score.
 *
 * @api
 */
interface ScoreInterface extends TeamScoreInterface
{
    /**
     * Add a scope to the score.
     *
     * @param ScopeInterface $scope The scope to add.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function add(ScopeInterface $scope);

    /**
     * Remove a scope from the score.
     *
     * @param ScopeInterface $scope The scope to remove.
     *
     * @throws InvalidArgumentException if the scope type is not supported by this sport.
     */
    public function remove(ScopeInterface $scope);

    /**
     * Get the scopes that make up the score.
     *
     * @return array<ScopeInterface>
     */
    public function scopes();
}
