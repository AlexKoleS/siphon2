<?php
namespace Icecave\Siphon\Player;

/**
 * Read data from player statistics feeds.
 *
 * @api
 */
interface PlayerStatisticsReaderInterface
{
    /**
     * Read a player statistics feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<tuple<PlayerInterface, PlayerStatisticsInterface>>
     */
    public function read($sport, $league, $season, $teamId);
}
