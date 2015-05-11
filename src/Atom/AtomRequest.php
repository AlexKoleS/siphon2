<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use InvalidArgumentException;

/**
 * A request to the atom feed.
 */
class AtomRequest implements RequestInterface
{
    /**
     * @param DateTime    $updatedTime Limit results to feeds updated after this time.
     * @param string|null $feed        Limit results to feeds of the given type, or null for any type.
     * @param integer     $limit       The maximum number of results to return.
     * @param integer     $order       The sort order (one of SORT_ASC or SORT_DESC).
     */
    public function __construct(
        DateTime $updatedTime,
        $feed = null,
        $limit = 5000,
        $order = SORT_ASC
    ) {
        $this->setUpdatedTime($updatedTime);
        $this->setFeed($feed);
        $this->setLimit($limit);
        $this->setOrder($order);
    }

    /**
     * Set the updated time limit.
     *
     * Results are limited to feeds updated after this time.
     *
     * @return DateTime The updated time.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Set the updated time limit.
     *
     * Results are limited to feeds updated after this time.
     *
     * @param DateTime $updatedTime The updated time.
     */
    public function setUpdatedTime(DateTime $updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }

    /**
     * Get the feed.
     *
     * Results are limited to feeds of the given type.
     *
     * @return string|null The feed filter, or null if all feeds are returned.
     */
    public function feed()
    {
        return $this->feed;
    }

    /**
     * Set the feed.
     *
     * Results are limited to feeds of the given type.
     *
     * @return string|null The feed filter, or null if all feeds are returned.
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
    }

    /**
     * Get the result limit.
     *
     * @return integer The maximum number of results to return.
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * Set the result limit.
     *
     * @param integer $limit The maximum number of results to return.
     */
    public function setLimit($limit)
    {
        if (!is_int($limit) || $limit < 1) {
            throw new InvalidArgumentException('Limit must be a positive integer.');
        }

        $this->limit = $limit;
    }

    /**
     * Get the result ordering.
     *
     * @return integer One of SORT_ASC or SORT_DESC.
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * Set the result ordering.
     *
     * @param integer $order One of SORT_ASC or SORT_DESC.
     */
    public function setOrder($order)
    {
        if (SORT_ASC !== $order && SORT_DESC !== $order) {
            throw new InvalidArgumentException('Sort order must be SORT_ASC or SORT_DESC.');
        }

        $this->order = $order;
    }

    /**
     * Dispatch a call to the given visitor.
     *
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitAtomRequest($this);
    }

    private $theshold;
    private $feed;
    private $limit;
    private $order;
}
