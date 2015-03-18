<?php
namespace Icecave\Siphon\Schedule;

/**
 * A client for SDI schedule feeds.
 *
 * @api
 */
interface ScheduleReaderInterface
{
    /**
     * Read a schedule feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return ScheduleInterface
     */
    public function read($sport, $league);
}
