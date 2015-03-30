<?php
namespace Icecave\Siphon\Score;

use InvalidArgumentException;

class InningScore implements InningScoreInterface
{
    use ScoreTrait;

    /**
     * @param integer $homeTeamHits   The number of hits made by the home team.
     * @param integer $awayTeamHits   The number of hits made by the away team.
     * @param integer $homeTeamErrors The number of errors made by the home team.
     * @param integer $awayTeamErrors The number of errors made by the away team.
     */
    public function __construct(
        $homeTeamHits = 0,
        $awayTeamHits = 0,
        $homeTeamErrors = 0,
        $awayTeamErrors = 0
    ) {
        $this->homeTeamHits   = $homeTeamHits;
        $this->awayTeamHits   = $awayTeamHits;
        $this->homeTeamErrors = $homeTeamErrors;
        $this->awayTeamErrors = $awayTeamErrors;
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

    private function checkScopeType(ScopeInterface $scope)
    {
        if (!$scope instanceof InningInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported scope type "%s", expected "%s".',
                    get_class($scope),
                     InningInterface::class
                )
            );
        }
    }

    private $homeTeamHits;
    private $awayTeamHits;
    private $homeTeamErrors;
    private $awayTeamErrors;
}
