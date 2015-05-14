<?php
namespace Icecave\Siphon\Atom;

use Countable;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use IteratorAggregate;

/**
 * A response from the atom feed.
 *
 * The response contains a set of requests for other feeds that have changed.
 */
class AtomResponse implements
    ResponseInterface,
    IteratorAggregate,
    Countable
{
    public function __construct(DateTime $updatedTime)
    {
        $this->requests = [];

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
     * Get the number of requests in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->requests);
    }

    /**
     * Iterate the requests.
     *
     * @return mixed<RequestInterface>
     */
    public function getIterator()
    {
        foreach ($this->requests as $request) {
            yield $request;
        }
    }

    /**
     * Add a request to the response.
     *
     * @param RequestInterface $request The request to add.
     */
    public function add(RequestInterface $request)
    {
        $this->requests[spl_object_hash($request)] = $request;
    }

    /**
     * Remove a request to the response.
     *
     * @param RequestInterface $request The request to add.
     */
    public function remove(RequestInterface $request)
    {
        unset($this->requests[spl_object_hash($request)]);
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

    private $requests;
    private $updatedTime;
}
