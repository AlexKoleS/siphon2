<?php
namespace Icecave\Siphon\Score;

class Period
{
    /**
     * @param PeriodType $type      The period type.
     * @param integer    $number    The sequential period number.
     * @param integer    $homeScore The home team score for this period.
     * @param integer    $awayScore The away team score for this period.
     */
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
     * Period numbers begin at one for each period type, for example in an NHL
     * game that has run to include all three shootout periods, the periods would
     * be numbered like so.
     *
     * - PERIOD 1
     * - PERIOD 2
     * - PERIOD 3
     * - OVERTIME 1
     * - SHOOTOUT 1
     * - SHOOTOUT 2
     * - SHOOTOUT 3
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
