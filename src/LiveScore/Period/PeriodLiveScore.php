<?php
namespace Icecave\Siphon\LiveScore\Period;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\LiveScore\GameTimeInterface;
use Icecave\Siphon\LiveScore\LiveScoreInterface;
use Icecave\Siphon\LiveScore\LiveScoreTrait;

/**
 * Live scores for period based sports.
 */
class PeriodLiveScore implements LiveScoreInterface, GameTimeInterface
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

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    public function scopeClass()
    {
        return Period::class;
    }

    private $gameTime;
}
