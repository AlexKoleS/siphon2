<?php
namespace Icecave\Siphon\LiveScore\Innings;

use Icecave\Siphon\LiveScore\LiveScoreInterface;
use Icecave\Siphon\LiveScore\LiveScoreTrait;

/**
 * Live score for innings-based sports.
 */
class InningsLiveScore implements LiveScoreInterface, InningsStatisticsInterface
{
    use LiveScoreTrait;

    /**
     * Get the number of hits made by the home team.
     *
     * @return integer
     */
    public function homeTeamHits()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->homeTeamHits();
        }

        return $result;
    }

    /**
     * Get the number of hits made by the away team.
     *
     * @return integer
     */
    public function awayTeamHits()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->awayTeamHits();
        }

        return $result;
    }

    /**
     * Get the number of errors made by the home team.
     *
     * @return integer
     */
    public function homeTeamErrors()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->homeTeamErrors();
        }

        return $result;
    }

    /**
     * Get the number of errors made by the away team.
     *
     * @return integer
     */
    public function awayTeamErrors()
    {
        $result = 0;

        foreach ($this->scopes() as $scope) {
            $result += $scope->awayTeamErrors();
        }

        return $result;
    }

    /**
     * Get the current innings type.
     *
     * @return InningsType|null The current innings type, or null if the game is complete.
     */
    public function currentInningsType()
    {
        return $this->currentInningsType;
    }

    /**
     * Set the current innings type.
     *
     * @param InningsType|null $type The current innings type, or null if the game is complete.
     */
    public function setCurrentInningsType(InningsType $type = null)
    {
        $this->currentInningsType = $type;
    }

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    public function scopeClass()
    {
        return Innings::class;
    }

    private $currentInningsType;
}
