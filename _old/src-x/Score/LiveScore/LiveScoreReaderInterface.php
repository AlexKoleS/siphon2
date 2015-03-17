<?php
namespace Icecave\Siphon\Score\LiveScore;

/**
 * Client for reading live score feeds.
 */
interface LiveScoreReaderInterface
{
    /**
     * Read a score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $competitionId The competition ID.
     *
     * @return ScoreInterface
     */
    public function read($sport, $league, $competitionId);
}
