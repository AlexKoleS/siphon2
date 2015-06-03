<?php
namespace Icecave\Siphon\Score;

/**
 * A score pair for a team-based sport.
 *
 * @api
 */
interface TeamScoreInterface
{
    /**
     * The home team's score.
     *
     * @return integer
     */
    public function homeTeamScore();

    /**
     * The away team's score.
     *
     * @return integer
     */
    public function awayTeamScore();
}
