<?php
namespace Icecave\Siphon\Score\LiveScore;

/**
 * The result of reading a live score feed for a competition that uses periods.
 *
 * @api
 */
interface PeriodResultInterface extends LiveScoreResultInterface
{
    /**
     * Get the current game time.
     *
     * @return Duration|null The current game time, or null if it is not currently available.
     */
    public function currentGameTime();
}
