<?php
namespace Icecave\Siphon\Atom;

use Countable;
use Icecave\Chrono\DateTime;
use IteratorAggregate;

/**
 * A collection of results from an Atom feed.
 */
class AtomResult implements Countable, IteratorAggregate
{
    public function __construct(DateTime $updatedTime)
    {
        $this->updatedTime = $updatedTime;
        $this->entries     = [];
    }

    /**
     * Add an entry to the collection.
     *
     * @param AtomEntry $entry The entry to add.
     */
    public function add(AtomEntry $entry)
    {
        $this->entries[] = $entry;
    }

    /**
     * Indicates whether or not the collection contains any entries.
     *
     * @reutrn boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->entries);
    }

    /**
     * Get the number of entries in the collection.
     *
     * @return integer The number of entries in the collection.
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Iterate over the entries.
     *
     * @return mixed<AtomEntry>
     */
    public function getIterator()
    {
        foreach ($this->entries as $entry) {
            yield $entry;
        }
    }

    /**
     * Get the time at which the atom result was produced.
     *
     * @return DateTime The time at which the atom result was produced.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    private $updatedTime;
    private $entries;
}
