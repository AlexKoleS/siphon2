<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;

/**
 * A single result from an atom feed.
 *
 * @api
 */
interface AtomEntryInterface
{
    /**
     * Get the URL of the feed that has been updated.
     *
     * @return string The URL of the feed that has been updated.
     */
    public function url();

    /**
     * Get the time at which the update occurred.
     *
     * @return DateTime The time at which update occurred.
     */
    public function updatedTime();
}
