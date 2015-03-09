<?php
namespace Icecave\Siphon\LiveScore\Innings;

use Icecave\Siphon\Score\ScopeInterface;
use Icecave\Siphon\Score\ScopeStatus;

/**
 * An innings.
 */
class Innings implements ScopeInterface, InningsStatisticsInterface
{
    /**
     * @param integer $homeTeamPoints The number of runs made by the home team.
     * @param integer $awayTeamPoints The number of runs made by the away team.
     * @param integer $homeTeamHits   The number of hits made by the home team.
     * @param integer $awayTeamHits   The number of hits made by the away team.
     * @param integer $homeTeamErrors The number of errors made by the home team.
     * @param integer $awayTeamErrors The number of errors made by the away team.
     */
    public function __construct(
        $homeTeamPoints,
        $awayTeamPoints,
        $homeTeamHits,
        $awayTeamHits,
        $homeTeamErrors,
        $awayTeamErrors
    ) {
        $this->status         = ScopeStatus::COMPLETE();
        $this->homeTeamPoints = $homeTeamPoints;
        $this->awayTeamPoints = $awayTeamPoints;
        $this->homeTeamHits   = $homeTeamHits;
        $this->awayTeamHits   = $awayTeamHits;
        $this->homeTeamErrors = $homeTeamErrors;
        $this->awayTeamErrors = $awayTeamErrors;
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
     * Get the number of runs made by the home team.
     *
     * @return integer
     */
    public function homeTeamPoints()
    {
        return $this->homeTeamPoints;
    }

    /**
     * Get the number of runs made by the away team.
     *
     * @return integer
     */
    public function awayTeamPoints()
    {
        return $this->awayTeamPoints;
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

    private $status;
    private $homeTeamPoints;
    private $awayTeamPoints;
    private $homeTeamHits;
    private $awayTeamHits;
    private $homeTeamErrors;
    private $awayTeamErrors;
}
