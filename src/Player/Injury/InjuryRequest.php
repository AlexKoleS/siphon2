<?php
namespace Icecave\Siphon\Player\Injury;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the player injury feed.
 */
class InjuryRequest implements SportRequestInterface
{
    /**
     * @param Sport $sport The sport to request.
     */
    public function __construct(Sport $sport)
    {
        $this->setSport($sport);
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
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitInjuryRequest($this);
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
            $this->sport->key()
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
            function ($sport) {
                $this->sport = Sport::memberByKey($sport);
            }
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'injury(%s)',
            $this->sport->key()
        );
    }

    private $sport;
}
