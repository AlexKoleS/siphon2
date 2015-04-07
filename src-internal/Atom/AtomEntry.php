<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;

/**
 * A single result from an Atom feed.
 */
class AtomEntry implements AtomEntryInterface
{
    /**
     * @param string   $url         The URL of the feed that has been updated.
     * @param DateTime $updatedTime The time at which the feed was updated.
     */
    public function __construct(
        $url,
        $resource,
        array $parameters,
        DateTime $updatedTime
    ) {
        $this->url         = $url;
        $this->resource    = $resource;
        $this->parameters  = $parameters;
        $this->updatedTime = $updatedTime;
    }

    /**
     * Get the URL of the feed that has been updated.
     *
     * @return string The URL of the feed that has been updated.
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Get the path to the feed.
     *
     * @return string The path to the feed.
     */
    public function resource()
    {
        return $this->resource;
    }

    /**
     * Get the URL parameters.
     *
     * @return array<string, mixed> Additional parameters to pass.
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Get the time at which the update occurred.
     *
     * @return DateTime The time at which update occurred.
     */
    public function updatedTime()
    {
        return $this->updatedTime;
    }

    private $url;
    private $resource;
    private $parameters;
    private $updatedTime;
}
