<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\ReaderInterface;

/**
 * Read data from player image feeds.
 *
 * @api
 */
interface ImageReaderInterface extends ReaderInterface
{
    /**
     * Read a player image feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<ImageInterface>
     */
    public function read($sport, $league, $season, $teamId);
}
