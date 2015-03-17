<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;

/**
 * Live scores for period based sports.
 */
class PeriodLiveScore extends PeriodScore implements LiveScoreInterface, GameTimeInterface
{
    use LiveScoreTrait;

    /**
     * Get the current game time.
     *
     * @return Duration|null The current game time.
     */
    public function gameTime()
    {
        return $this->gameTime;
    }

    /**
     * Set the current game time.
     *
     * @param Duration|null $gameTime The current game time.
     */
    public function setGameTime(Duration $gameTime = null)
    {
        $this->gameTime = $gameTime;
    }

    private $gameTime;
}
