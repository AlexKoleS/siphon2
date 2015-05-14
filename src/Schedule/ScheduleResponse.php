<?php
namespace Icecave\Siphon\Schedule;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Sport;
use IteratorAggregate;

/**
 * The response from a schedule feed.
 */
class ScheduleResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    public function __construct(Sport $sport, ScheduleType $type)
    {
        $this->setSport($sport);
        $this->setType($type);
        $this->seasons = [];
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
     * Get the number of seasons in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->seasons);
    }

    /**
     * Iterate the seasons.
     *
     * @return mixed<Season>
     */
    public function getIterator()
    {
        foreach ($this->seasons as $season) {
            yield $season;
        }
    }

    /**
     * Add a season to the response.
     *
     * @param Season $season The season to add.
     */
    public function add(SeasonInterface $season)
    {
        $this->seasons[$season->id()] = $season;
    }

    /**
     * Remove a season from the response.
     *
     * @param Season $season The season to remove.
     */
    public function remove(SeasonInterface  $season)
    {
        unset($this->seasons[$season->id()]);
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
        return $visitor->visitScheduleResponse($this);
    }

    private $sport;
    private $type;
    private $seasons;
}
