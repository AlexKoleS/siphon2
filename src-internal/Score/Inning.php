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
     */
    public function __construct($homeTeamScore, $awayTeamScore)
    {
        $this->homeTeamScore  = $homeTeamScore;
        $this->awayTeamScore  = $awayTeamScore;
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

    private $homeTeamScore;
    private $awayTeamScore;
}
