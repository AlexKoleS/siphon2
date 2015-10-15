<?php

namespace Icecave\Siphon\Team\Statistics;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamRequestTrait;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the Team statistics feed.
 */
class TeamStatisticsRequest implements SportRequestInterface
{
    use TeamRequestTrait {
        __construct as private initialize;
    }

    /**
     * @param Sport               $sport      The sport to request.
     * @param string              $seasonName The season name.
     * @param StatisticsType|null $type       The type of statistics to fetch.
     */
    public function __construct(
        Sport $sport,
        $seasonName,
        StatisticsType $type = null
    ) {
        if (null === $type) {
            $type = StatisticsType::COMBINED();
        }

        $this->initialize($sport, $seasonName);
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
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitTeamStatisticsRequest($this);
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
            $this->seasonName,
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
            function ($sport, $seasonName, $type) {
                $this->sport = Sport::memberByKey($sport);
                $this->seasonName = $seasonName;
                $this->type = StatisticsType::memberByKey($type);
            }
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'team-statistics(%s %s %s)',
            $this->sport->key(),
            $this->seasonName,
            $this->type->value()
        );
    }

    private $type;
}
