<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the team feed.
 */
class TeamRequest implements SportRequestInterface
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
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor)
    {
        return $visitor->visitTeamRequest($this);
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
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            'team(%s %s)',
            $this->sport->key(),
            $this->seasonName
        );
    }

    private $sport;
    private $seasonName;
}
