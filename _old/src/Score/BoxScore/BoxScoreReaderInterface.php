<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\ReaderInterface;

/**
 * A client for reading SDI box score feeds.
 *
 * @api
 */
interface BoxScoreReaderInterface extends ReaderInterface
{
    /**
     * Read a box score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $season        The season.
     * @param string $competitionId The competition ID.
     *
     * @return BoxScoreResultInterface
     */
    public function read($sport, $league, $season, $competitionId);
}
