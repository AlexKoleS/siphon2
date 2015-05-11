<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\ReaderInterface;

/**
 * Read data from player feeds.
 *
 * @api
 */
interface PlayerReaderInterface extends ReaderInterface
{
    /**
     * Read a player feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<tuple<PlayerInterface, SeasonDetailsInterface>>
     */
    public function read($sport, $league, $season, $teamId);
}
