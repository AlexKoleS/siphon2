<?php
namespace Icecave\Siphon\Score;

/**
 * Period for team sports.
 */
class Period implements PeriodInterface
{
    /**
     * @param PeriodType $periodType    The period type.
     * @param integer    $homeTeamScore The number of points made by the home team.
     * @param integer    $awayTeamScore The number of points made by the away team.
     */
    public function __construct(
        PeriodType $periodType,
        $homeTeamScore = 0,
        $awayTeamScore = 0
    ) {
        $this->type          = $periodType;
        $this->homeTeamScore = $homeTeamScore;
        $this->awayTeamScore = $awayTeamScore;
    }

    /**
     * Get the type of the period.
     *
     * @return PeriodType The period type.
     */
    public function type()
    {
        return $this->type;
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
     * Get the number of points made by the away team.
     *
     * @return integer
     */
    public function awayTeamScore()
    {
        return $this->awayTeamScore;
    }

    private $type;
    private $homeTeamScore;
    private $awayTeamScore;
}
