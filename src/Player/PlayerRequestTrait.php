<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\IdParser;
use Icecave\Siphon\Util\Serialization;

/**
 * Common implementation for requests that operate per sport + season + team.
 */
trait PlayerRequestTrait
{
    /**
     * @param Sport          $sport      The sport to request.
     * @param string         $seasonName The season name.
     * @param string|integer $teamId     The team ID.
     */
    public function __construct(
        Sport $sport,
        $seasonName,
        $teamId
    ) {
        $this->setSport($sport);
        $this->setSeasonName($seasonName);
        $this->setTeamId($teamId);
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
     * Get the ID of the requested team.
     *
     * @return integer The numeric team ID.
     */
    public function teamId()
    {
        return $this->teamId;
    }

    /**
     * Set the team ID of the requested team.
     *
     * If a string ID is given it is validated against the sport, and the
     * numeric portion is extracted.
     *
     * @param string|integer The string or numeric team ID.
     */
    public function setTeamId($teamId)
    {
        $this->teamId = IdParser::parse(
            $teamId,
            $this->sport,
            'team'
        );
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
            $this->teamId
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
            function ($sport, $seasonName, $teamId) {
                $this->sport = Sport::memberByKey($sport);
                $this->seasonName = $seasonName;
                $this->teamId = $teamId;
            }
        );
    }

    private $sport;
    private $seasonName;
    private $teamId;
}
