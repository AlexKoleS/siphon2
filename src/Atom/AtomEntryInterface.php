<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;

/**
 * A single Atom result.
 *
 * An Atom entry provides the URL and modification timestamp of each feed listed
 * in the Atom feed.
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
     * Get the path to the feed.
     *
     * @return string The path to the feed.
     */
    public function resource();

    /**
     * Get the URL parameters.
     *
     * @return array<string, mixed> Additional parameters to pass.
     */
    public function parameters();

    /**
     * Get the time at which the update occurred.
     *
     * @return DateTime The time at which update occurred.
     */
    public function updatedTime();
}
