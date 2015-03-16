<?php
namespace Icecave\Siphon\Score;

use Icecave\Siphon\Schedule\Competition;

/**
 * Client for reading score feeds.
 */
interface ScoreReaderInterface
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
