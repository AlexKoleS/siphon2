<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;

class PeriodResult implements PeriodResultInterface
{
    use ResultTrait;

    /**
     * Get the current game time.
     *
     * @return Duration|null The current game time, or null if it is not currently available.
     */
    public function currentGameTime()
    {
        return $this->gameTime;
    }

    /**
     * Set the current game time.
     *
     * @param Duration|null The current game time, or null if it is not currently available.
     */
    public function setCurrentGameTime(Duration $gameTime = null)
    {
        $this->gameTime = $gameTime;
    }

    private $gameTime;
}
