<?php
namespace Icecave\Siphon\LiveScore;

class LiveScore
{
    /**
     * Get the current game clock time.
     *
     * @return TimeSpanInterface
     */
    public function gameClock();

    /**
     * @return Scope|null The current scope, or null if the competition is not in-progress.
     */
    public function currentScope();

    /**
     * @return array<Scope>
     */
    public function scopes();
}
