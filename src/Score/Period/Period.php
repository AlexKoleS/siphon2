<?php
namespace Icecave\Siphon\Score\Period;

class Period
{
    public function __construct(
        PeriodType $type,
        $number,
        $homeScore = 0,
        $awayScore = 0
    ) {
        $this->type      = $type;
        $this->number    = $number;
        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;
    }

    /**
     * Get the scope type.
     *
     * @return PeriodType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Get the sequential period number.
     *
     * @return integer
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * Get the home team score.
     *
     * @return integer
     */
    public function homeScore()
    {
        return $this->homeScore;
    }

    /**
     * Get the away team score.
     *
     * @return integer
     */
    public function awayScore()
    {
        return $this->awayScore;
    }

    private $type;
    private $number;
    private $homeScore;
    private $awayScore;
}
