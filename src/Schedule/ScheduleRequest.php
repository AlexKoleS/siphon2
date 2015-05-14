<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;

/**
 * A request to the schedule feed.
 */
class ScheduleRequest implements RequestInterface
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

    private $sport;
    private $type;
}
