<?php
namespace Icecave\Siphon\Score;

/**
 * A scope for sports that use innings (baseball).
 */
class Inning implements InningInterface
{
    /**
     * @param integer $homeTeamScore  The number of runs made by the home team.
     * @param integer $awayTeamScore  The number of runs made by the away team.
     * @param integer $homeTeamHits   The number of hits made by the home team.
     * @param integer $awayTeamHits   The number of hits made by the away team.
     * @param integer $homeTeamErrors The number of errors made by the home team.
     * @param integer $awayTeamErrors The number of errors made by the away team.
     */
    public function __construct(
        $homeTeamScore,
        $awayTeamScore,
        $homeTeamHits,
        $awayTeamHits,
        $homeTeamErrors,
        $awayTeamErrors
    ) {
        $this->homeTeamScore  = $homeTeamScore;
        $this->awayTeamScore  = $awayTeamScore;
        $this->homeTeamHits   = $homeTeamHits;
        $this->awayTeamHits   = $awayTeamHits;
        $this->homeTeamErrors = $homeTeamErrors;
        $this->awayTeamErrors = $awayTeamErrors;
    }

    /**
     * Get the number of runs made by the home team.
     *
     * @return integer
     */
    public function homeTeamScore()
    {
        return $this->homeTeamScore;
    }

    /**
     * Get the number of runs made by the away team.
     *
     * @return integer
     */
    public function awayTeamScore()
    {
        return $this->awayTeamScore;
    }

    /**
     * Get the number of hits made by the home team.
     *
     * @return integer
     */
    public function homeTeamHits()
    {
        return $this->homeTeamHits;
    }

    /**
     * Get the number of hits made by the away team.
     *
     * @return integer
     */
    public function awayTeamHits()
    {
        return $this->awayTeamHits;
    }

    /**
     * Get the number of errors made by the home team.
     *
     * @return integer
     */
    public function homeTeamErrors()
    {
        return $this->homeTeamErrors;
    }

    /**
     * Get the number of errors made by the away team.
     *
     * @return integer
     */
    public function awayTeamErrors()
    {
        return $this->awayTeamErrors;
    }

    private $homeTeamScore;
    private $awayTeamScore;
    private $homeTeamHits;
    private $awayTeamHits;
    private $homeTeamErrors;
    private $awayTeamErrors;
}
