<?php
namespace Icecave\Siphon\Score\LiveScore;

interface PeriodLiveScoreInterface extends LiveScoreInterface
{
    /**
     * Get the current game time.
     *
     * @return Duration|null The current game time, or null if it is not currently available.
     */
    public function currentGameTime();
}
