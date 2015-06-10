<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;

/**
 * The response from a team feed.
 */
class LiveScoreResponse implements ResponseInterface
{
    public function __construct(
        CompetitionInterface $competition,
        Score $score
    ) {
        $this->setCompetition($competition);
        $this->setScore($score);
    }

    /**
     * Get the requested competition.
     *
     * @return CompetitionInterface
     */
    public function competition()
    {
        return $this->competition;
    }

    /**
     * Set the requested competition.
     *
     * @param CompetitionInterface $competition
     */
    public function setCompetition(CompetitionInterface $competition)
    {
        $this->competition = $competition;
    }

    /**
     * Get the competition score.
     *
     * @return Score
     */
    public function score()
    {
        return $this->score;
    }

    /**
     * Set the score.
     *
     * @param Score $score
     */
    public function setScore(Score $score)
    {
        $this->score = $score;
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
        return $visitor->visitLiveScoreResponse($this);
    }

    private $competition;
    private $score;
}
