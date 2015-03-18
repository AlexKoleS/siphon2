<?php
namespace Icecave\Siphon\Score\LiveScore;

/**
 * The result of reading a live score feed for a competition that uses innings.
 *
 * @api
 */
interface InningResultInterface extends LiveScoreResultInterface
{
    /**
     * Get the current sub-type of the current inning.
     *
     * @return InningSubType|null The current inning sub-type (top or bottom), or null if no inning is in progress.
     */
    public function currentInningSubType();
}
