<?php
namespace Icecave\Siphon\Atom;

use ArrayIterator;
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
     * @param AtomEntryInterface $entry The entry to add.
     */
    public function add(AtomEntryInterface $entry)
    {
        $this->entries[] = $entry;
    }

    /**
     * Remove an entry from the collection.
     *
     * @param AtomEntryInterface $entry The entry to remove.
     */
    public function remove(AtomEntryInterface $entry)
    {
        $index = array_search($entry, $this->entries);

        if (false !== $index) {
            array_splice($this->entries, $index, 1);
        }
    }

    /**
     * Get an array of the entries.
     *
     * @return array<AtomEntryInterface>
     */
    public function entries()
    {
        return $this->entries;
    }

    /**
     * Indicates whether or not the collection contains any entries.
     *
     * @return boolean True if the collection is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->entries);
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
        return new ArrayIterator($this->entries);
    }

    private $updatedTime;
    private $entries;
}
