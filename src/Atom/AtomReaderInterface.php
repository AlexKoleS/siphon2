<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;

/**
 * Read and parse atom feeds.
 *
 * Atom feeds are used to determine when any of the data feeds have been updated.
 */
interface AtomReaderInterface
{
    /**
     * Fetch a list of feeds that have been updated since the given time.
     *
     * @param DateTime    $threshold Feeds updated after this time point are included in the result.
     * @param string|null $feed      Limit results to feeds of the given type, or null for any type.
     * @param integer     $limit     The maximum number of results to return.
     * @param integer     $order     The sort order (one of SORT_ASC or SORT_DESC).
     *
     * @return AtomResult
     */
    public function read(
        DateTime $threshold,
        $feed = null,
        $limit = 5000,
        $order = SORT_ASC
    );
}
