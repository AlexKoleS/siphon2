<?php
namespace Icecave\Siphon\Score\LiveScore;

interface InningLiveScoreInterface extends LiveScoreInterface
{
    /**
     * Get the current scope, if one is in-progress.
     *
     * @return InningHalf|null The current inning-half (top or bottom), or null if no inning is in progress.
     */
    public function currentInningHalf();
}
