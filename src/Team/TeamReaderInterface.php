<?php
namespace Icecave\Siphon\Team;

/**
 * Read data from team feeds.
 *
 * @api
 */
interface TeamReaderInterface
{
    /**
     * Read a team feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     *
     * @return array<TeamInterface>
     */
    public function read($sport, $league, $season);
}
