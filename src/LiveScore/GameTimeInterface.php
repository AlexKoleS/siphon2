<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;

/**
 * Live-score interface for games that use a game clock.
 */
interface GameTimeInterface
{
    /**
     * Get the current game time.
     *
     * @return Duration|null The current game time.
     */
    public function gameTime();

    /**
     * Set the current game time.
     *
     * @param Duration|null $gameType The current game time.
     */
    public function setGameTime(Duration $gameTime = null);
}
