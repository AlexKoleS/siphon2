<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Siphon\ReaderInterface;

/**
 * A client for SDI schedule feeds.
 *
 * @api
 */
interface ScheduleReaderInterface extends ReaderInterface
{
    /**
     * Read a schedule feed.
     *
     * @param string             $sport  The sport (eg, baseball, football, etc)
     * @param string             $league The league (eg, MLB, NFL, etc)
     * @param ScheduleLimit|null $limit  Limit results to a competitions within a certain timeframe.
     *
     * @return ScheduleInterface
     */
    public function read($sport, $league, ScheduleLimit $limit = null);

    /**
     * Read the deleted schedule feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return ScheduleInterface
     */
    public function readDeleted($sport, $league);
}
