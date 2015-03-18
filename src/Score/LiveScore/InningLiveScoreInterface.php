<?php
namespace Icecave\Siphon\Score\LiveScore;

interface InningLiveScoreInterface extends LiveScoreInterface
{
    /**
     * Get the current sub-type of the current inning.
     *
     * @return InningSubType|null The current inning sub-type (top or bottom), or null if no inning is in progress.
     */
    public function currentInningSubType();
}
