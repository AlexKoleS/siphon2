<?php
namespace Icecave\Siphon\Atom;

use Countable;
use Icecave\Chrono\DateTime;
use IteratorAggregate;

/**
 * A collection of results from an Atom feed.
 *
 * @api
 */
interface AtomResultInterface extends Countable, IteratorAggregate
{
    /**
     * Add an entry to the collection.
     *
     * @param AtomEntryInterface $entry The entry to add.
     */
    public function add(AtomEntryInterface $entry);

    /**
     * Remove an entry from the collection.
     *
     * @param AtomEntryInterface $entry The entry to remove.
     */
    public function remove(AtomEntryInterface $entry);

    /**
     * Get an array of the entries.
     *
     * @return array<AtomEntryInterface>
     */
    public function entries();

    /**
     * Indicates whether or not the collection contains any entries.
     *
     * @return boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty();

    /**
     * Get the time at which the atom result was produced.
     *
     * @return DateTime The time at which the atom result was produced.
     */
    public function updatedTime();
}
