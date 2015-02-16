<?php
namespace Icecave\Sid\Atom;

use Countable;
use Icecave\Chrono\DateTime;
use IteratorAggregate;

/**
 * A collection of results from an Atom feed.
 */
class AtomResult implements Countable, IteratorAggregate
{
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
     * Get the updated time of the most recently updated entry in the collection.
     *
     * @return DateTime|null The time of the most recently updated entry, or null if the collection is empty.
     */
    public function updatedTime()
    {
        $updatedTime = null;

        foreach ($this->entries as $entry) {
            if (null === $updatedTime) {
                $updatedTime = $entry->updatedTime();
            } elseif ($entry->updatedTime()->isGreaterThan($updatedTime)) {
                $updatedTime = $entry->updatedTime();
            }
        }

        return $updatedTime;
    }

    private $entries = [];
}
