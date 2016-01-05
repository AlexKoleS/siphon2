<?php

namespace Icecave\Siphon\Team\Statistics;

use Countable;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamInterface;
use Icecave\Siphon\Team\TeamResponseTrait;
use IteratorAggregate;

/**
 * The response from a team statistics feed.
 */
class TeamStatisticsResponse implements
    ResponseInterface,
    Countable,
    IteratorAggregate
{
    use ResponseTrait;
    use TeamResponseTrait {
        __construct as private initialize;
    }

    /**
     * @param Sport          $sport  The sport.
     * @param Season         $season The season.
     * @param StatisticsType $type   The type of statistics.
     */
    public function __construct(
        Sport $sport,
        Season $season,
        StatisticsType $type
    ) {
        $this->initialize($sport, $season);
        $this->setType($type);
    }

    /**
     * Get the requested statistics type.
     *
     * @return StatisticsType The requested statistics type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Set the requested statistics type.
     *
     * @param StatisticsType $type The requested statistics type.
     */
    public function setType(StatisticsType $type)
    {
        $this->type = $type;
    }

    /**
     * Add a team to the response.
     *
     * @param TeamInterface        $team       The team to add.
     * @param StatisticsCollection $statistics The team's statistics.
     */
    public function add(TeamInterface $team, StatisticsCollection $statistics)
    {
        $this->entries[$team->id()] = [$team, $statistics];
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
        return $visitor->visitTeamStatisticsResponse($this);
    }

    private $type;
}
