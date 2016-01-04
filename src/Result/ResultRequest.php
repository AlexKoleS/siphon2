<?php

namespace Icecave\Siphon\Result;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the result feed.
 */
class ResultRequest implements SportRequestInterface
{
    /**
     * @param Sport  $sport      The sport to request.
     * @param string $seasonName The season name.
     */
    public function __construct(Sport $sport, $seasonName)
    {
        $this->setSport($sport);
        $this->setSeasonName($seasonName);
    }

    /**
     * Get the requested sport.
     *
     * @return Sport The requested sport.
     */
    public function sport()
    {
        return $this->sport;
    }

    /**
     * Set the requested sport.
     *
     * @param Sport $sport The requested sport.
     */
    public function setSport(Sport $sport)
    {
        $this->sport = $sport;
    }

    /**
     * Get the name of the requested season.
     *
     * @return string The season name.
     */
    public function seasonName()
    {
        return $this->seasonName;
    }

    /**
     * Set the name of the requested season.
     *
     * @param string $seasonName The season name.
     */
    public function setSeasonName($seasonName)
    {
        $this->seasonName = $seasonName;
    }

    /**
     * Serialize the request to a buffer.
     *
     * @return string
     */
    public function serialize()
    {
        return Serialization::serialize(
            1, // version 1
            $this->sport->key(),
            $this->seasonName
        );
    }

    /**
     * Unserialize a serialized request from a buffer.
     *
     * @param string $buffer
     */
    public function unserialize($buffer)
    {
        Serialization::unserialize(
            $buffer,
            function ($sport, $seasonName) {
                $this->sport = Sport::memberByKey($sport);
                $this->seasonName = $seasonName;
            }
        );
    }


    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitResultRequest($this);
    }

    /**
     * Fetch the request's "rate-limit group".
     *
     * If the request is rate-limited, any other requests that are in the same
     * rate-limit group are also rate limited.
     *
     * @return string The rate-limit group.
     */
    public function rateLimitGroup()
    {
        return sprintf(
            'result(%s)',
            $this->sport->key()
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'result(%s %s)',
            $this->sport->key(),
            $this->seasonName
        );
    }

    private $sport;
    private $seasonName;
}
