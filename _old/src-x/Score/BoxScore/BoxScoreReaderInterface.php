<?php
namespace Icecave\Siphon\Score\BoxScore;

/**
 * Client for reading boxscore feeds.
 */
interface BoxScoreReaderInterface
{
    /**
     * Read a score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $season        The season.
     * @param string $competitionId The competition ID.
     *
     * @return ScoreInterface
     */
    public function read($sport, $league, $season, $competitionId);
}
