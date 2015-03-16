<?php
namespace Icecave\Siphon\Schedule;

/**
 * Client for reading schedule feeds.
 */
interface ScheduleReaderInterface
{
    /**
     * Read a schedule feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return Schedule
     */
    public function read($sport, $league);
}
