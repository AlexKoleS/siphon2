<?php
namespace Icecave\Siphon\Atom;

use Countable;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use IteratorAggregate;

/**
 * A response from the atom feed.
 */
class AtomResponse implements
    ResponseInterface,
    IteratorAggregate,
    Countable
{
    public function __construct(DateTime $updatedTime)
    {
        $this->entries = [];

        $this->setUpdatedTime($updatedTime);
    }

    /**
     * Get the time at which the atom response was produced.
     *
     * @return DateTime The time at which the atom response was produced.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Set the time at which the atom response was produced.
     *
     * @param DateTime $updatedTime The time at which the atom response was produced.
     */
    public function setUpdatedTime(DateTime $updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }

    /**
     * Get the number of entries in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Iterate the entries.
     *
     * An entry is a 2-tuple of URL and modification time.
     *
     * @return mixed<tuple<string, DateTime>> A sequence of entries.
     */
    public function getIterator()
    {
        foreach ($this->entries as $entry) {
            yield $entry;
        }
    }

    /**
     * Add an entry to the response.
     *
     * @param string   $url         The feed URL.
     * @param DateTime $updatedTime The last modification time of the feed.
     */
    public function add($url, DateTime $updatedTime)
    {
        $this->entries[$url] = [$url, $updatedTime];
    }

    /**
     * Remove an entry from the response.
     *
     * @param string $url The feed URL.
     */
    public function remove($url)
    {
        unset($this->entries[$url]);
    }

    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor)
    {
        return $visitor->visitAtomResponse($this);
    }

    private $entries;
    private $updatedTime;
}
