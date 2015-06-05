<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\SportRequestInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Util\IdParser;
use Icecave\Siphon\Util\Serialization;

/**
 * A request to the live score feed.
 */
class LiveScoreRequest implements SportRequestInterface
{
    /**
     * @param Sport          $sport         The sport to request.
     * @param string|integer $competitionId The competition ID.
     */
    public function __construct(Sport $sport, $competitionId)
    {
        $this->setSport($sport);
        $this->setCompetitionId($competitionId);
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
     * Get the ID of the requested competition.
     *
     * @return integer The numeric competition ID.
     */
    public function competitionId()
    {
        return $this->competitionId;
    }

    /**
     * Set the ID of the requested competition.
     *
     * If a string ID is given it is validated against the sport, and the
     * numeric portion is extracted.
     *
     * @param string|integer The string or numeric competition ID.
     */
    public function setCompetitionId($seasonName)
    {
        $this->competitionId = IdParser::parse(
            $competitionId,
            $this->sport,
            'competition'
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
        return $visitor->visitLiveScoreRequest($this);
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
            $this->competitionId
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
            function ($sport, $competitionId) {
                $this->sport = Sport::memberByKey($sport);
                $this->competitionId = $competitionId;
            }
        );
    }

    private $sport;
    private $competitionId;
}
