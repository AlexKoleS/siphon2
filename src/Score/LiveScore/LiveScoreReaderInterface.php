<?php
namespace Icecave\Siphon\Score\LiveScore;

/**
 * A client for reading SDI live score feeds.
 *
 * @api
 */
interface LiveScoreReaderInterface
{
    /**
     * Read a live score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $competitionId The competition ID.
     *
     * @return LiveScoreResultInterface
     */
    public function read($sport, $league, $competitionId);
}
