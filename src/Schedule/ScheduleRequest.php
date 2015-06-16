<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the schedule feed.
 */
class ScheduleRequest implements SportRequestInterface
{
    /**
     * @param Sport        $sport The sport to request.
     * @param ScheduleType $type  The type of schedule feed to request.
     */
    public function __construct(Sport $sport, ScheduleType $type = null)
    {
        if (null === $type) {
            $type = ScheduleType::FULL();
        }

        $this->setSport($sport);
        $this->setType($type);
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
     * Get the requested schedule type.
     *
     * @return ScheduleType The requested schedule type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Set the requested schedule type.
     *
     * @param ScheduleType $type The requested schedule type.
     */
    public function setType(ScheduleType $type)
    {
        $this->type = $type;
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
        return $visitor->visitScheduleRequest($this);
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
            $this->type->key()
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
            function ($sport, $type) {
                $this->sport = Sport::memberByKey($sport);
                $this->type  = ScheduleType::memberByKey($type);
            }
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (ScheduleType::FULL() === $this->type) {
            return sprintf(
                'schedule(%s)',
                $this->sport->key()
            );
        } elseif (ScheduleType::DELETED() === $this->type) {
            return sprintf(
                'schedule(%s deleted)',
                $this->sport->key()
            );
        } else {
            return sprintf(
                'schedule(%s %d days)',
                $this->sport->key(),
                $this->type->value()
            );
        }
    }

    private $sport;
    private $type;
}
