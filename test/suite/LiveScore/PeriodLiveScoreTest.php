<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use PHPUnit_Framework_TestCase;

class PeriodLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->liveScore = new PeriodLiveScore;
    }

    public function testGameTime()
    {
        $this->assertNull(
            $this->liveScore->gameTime()
        );

        $gameTime = new Duration;

        $this->liveScore->setGameTime($gameTime);

        $this->assertSame(
            $gameTime,
            $this->liveScore->gameTime()
        );

        $this->liveScore->setGameTime(null);

        $this->assertNull(
            $this->liveScore->gameTime()
        );
    }
}
