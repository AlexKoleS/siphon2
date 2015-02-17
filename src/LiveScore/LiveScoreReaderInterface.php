<?php
namespace Icecave\Siphon\LiveScore;

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
     * @return LiveScoreResult
     */
    public function read(Competition $competition);
}
