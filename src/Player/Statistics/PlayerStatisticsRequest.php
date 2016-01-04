<?php

namespace Icecave\Siphon\Player\Statistics;

use Icecave\Siphon\Player\PlayerRequestTrait;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the player statistics feed.
 */
class PlayerStatisticsRequest implements SportRequestInterface
{
    use PlayerRequestTrait {
        __construct as private initialize;
    }

    /**
     * @param Sport               $sport      The sport to request.
     * @param string              $seasonName The season name.
     * @param string|integer      $teamId     The team ID.
     * @param StatisticsType|null $type       The type of statistics to fetch.
     */
    public function __construct(
        Sport $sport,
        $seasonName,
        $teamId,
        StatisticsType $type = null
    ) {
        if (null === $type) {
            $type = StatisticsType::COMBINED();
        }

        $this->initialize($sport, $seasonName, $teamId);
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
            $this->teamId,
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
            function ($sport, $seasonName, $teamId, $type) {
                $this->sport = Sport::memberByKey($sport);
                $this->seasonName = $seasonName;
                $this->teamId = $teamId;
                $this->type = StatisticsType::memberByKey($type);
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
        return $visitor->visitPlayerStatisticsRequest($this);
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
            'player-statistics(%s)',
            $this->sport->key()
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'player-statistics(%s %s team:%d %s)',
            $this->sport->key(),
            $this->seasonName,
            $this->teamId,
            $this->type->value()
        );
    }

    private $type;
}
