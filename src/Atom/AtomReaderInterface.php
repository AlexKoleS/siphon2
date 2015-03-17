<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;

/**
 * Read data from the Atom feed.
 *
 * Atom feeds are used to poll for changes to the sports data feeds.
 *
 * @api
 */
interface AtomReaderInterface
{
    /**
     * Fetch a list of feeds that have been updated since the given threshold
     * time.
     *
     * @param DateTime    $threshold Limit results to feeds updated after this time point.
     * @param string|null $feed      Limit results to feeds of the given type, or null for any type.
     * @param integer     $limit     The maximum number of results to return.
     * @param integer     $order     The sort order (one of SORT_ASC or SORT_DESC).
     *
     * @return AtomResultInterface
     */
    public function read(
        DateTime $threshold,
        $feed = null,
        $limit = 5000,
        $order = SORT_ASC
    );
}
