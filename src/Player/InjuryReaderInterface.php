<?php
namespace Icecave\Siphon\Player;

/**
 * Read data from player injury feeds.
 *
 * @api
 */
interface InjuryReaderInterface
{
    /**
     * Read a player injury feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return array<InjuryInterface>
     */
    public function read($sport, $league);
}
