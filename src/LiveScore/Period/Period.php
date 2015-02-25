<?php
namespace Icecave\Siphon\LiveScore\Period;

use Icecave\Siphon\LiveScore\ScopeInterface;
use Icecave\Siphon\LiveScore\ScopeStatus;

/**
 * Period for team sports.
 */
class Period implements ScopeInterface
{
    /**
     * @param integer $homeTeamPoints The number of points made by the home team.
     * @param integer $awayTeamPoints The number of points made by the away team.
     */
    public function __construct(
        $homeTeamPoints = 0,
        $awayTeamPoints = 0
    ) {
        $this->status         = ScopeStatus::COMPLETE();
        $this->type           = PeriodType::PERIOD();
        $this->homeTeamPoints = $homeTeamPoints;
        $this->awayTeamPoints = $awayTeamPoints;
    }

    /**
     * Get the status of the scope.
     *
     * @return ScopeStatus
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Set the status of the scope.
     *
     * @param ScopeStatus $status
     */
    public function setStatus(ScopeStatus $status)
    {
        $this->status = $status;
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
     * Set the type of the period.
     *
     * @param PeriodType $type The period type.
     */
    public function setType(PeriodType $type)
    {
        return $this->type = $type;
    }

    /**
     * Get the number of runs made by the home team.
     *
     * @return integer
     */
    public function homeTeamPoints()
    {
        return $this->homeTeamPoints;
    }

    /**
     * Get the number of points made by the away team.
     *
     * @return integer
     */
    public function awayTeamPoints()
    {
        return $this->awayTeamPoints;
    }

    private $status;
    private $type;
    private $homeTeamPoints;
    private $awayTeamPoints;
}
