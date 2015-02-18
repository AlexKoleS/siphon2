<?php
namespace Icecave\Siphon\LiveScore;

/**
 * Client for reading live score feeds.
 */
class LiveScoreReader implements LiveScoreReaderInterface
{
    /**
     * Read a live score feed for a competition.
     *
     * @param Competition $competition The competition.
     *
     * @return LiveScoreResult
     */
    public function read(Competition $competition)
    {
        list(, $id) = explode(':', $competition->id(), 2);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/livescores/livescores_%d.xml',
                    strtolower($competition->sport()),
                    strtoupper($competition->league()),
                    $id
                )
            )
            ->{'team-sport-content'}
            ->{'league-content'}
            ->{'competition'};

            // scope (period)
            // scope status
            // game clock

            // periods + overtime + shootouts + total
            // OR
            // innings + total (runs + hits + errors)
    }
}
