<?php
namespace Icecave\Siphon\Score;

/**
 * Additional statistics for innings-based sports.
 *
 * @api
 */
interface InningStatisticsInterface
{
    /**
     * Get the number of hits made by the home team.
     *
     * @return integer
     */
    public function homeTeamHits();

    /**
     * Get the number of hits made by the away team.
     *
     * @return integer
     */
    public function awayTeamHits();

    /**
     * Get the number of errors made by the home team.
     *
     * @return integer
     */
    public function homeTeamErrors();

    /**
     * Get the number of errors made by the away team.
     *
     * @return integer
     */
    public function awayTeamErrors();
}
