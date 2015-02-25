<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Schedule\Competition;

/**
 * Client for reading live score feeds.
 */
interface LiveScoreReaderInterface
{
    /**
     * Read a live score feed for a competition.
     *
     * @param Competition $competition The competition.
     *
     * @return LiveScoreInterface
     */
    public function read(Competition $competition);
}
