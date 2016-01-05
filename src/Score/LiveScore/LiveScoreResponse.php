<?php

namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\ResponseTrait;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\Score;

/**
 * The response from a live score feed.
 */
class LiveScoreResponse implements ResponseInterface
{
    use ResponseTrait;

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
     * Get the current period.
     *
     * @return Period|null The current period, if there is one.
     */
    public function currentPeriod()
    {
        return $this->currentPeriod;
    }

    /**
     * Set the current period.
     *
     * @param Period|null $period The current period, if there is one.
     */
    public function setCurrentPeriod(Period $period = null)
    {
        $this->currentPeriod = $period;
    }

    /**
     * Get the current game time, if available.
     *
     * The game time is only available when a period is in progress. Some sports
     * do not use a game time at all.
     *
     * @return Duration|null The current game time, or null if it is not available.
     */
    public function gameTime()
    {
        return $this->gameTime;
    }

    /**
     * Set the current game time, if available.
     *
     * @param Duration|null $gameTime The current game time, or null if it is not available.
     */
    public function setGameTime(Duration $gameTime = null)
    {
        $this->gameTime = $gameTime;
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
    private $currentPeriod;
    private $gameTime;
}
