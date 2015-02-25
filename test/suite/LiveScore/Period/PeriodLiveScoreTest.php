<?php
namespace Icecave\Siphon\LiveScore\Period;

use Icecave\Chrono\TimeSpan\Duration;
use PHPUnit_Framework_TestCase;

class PeriodLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = new Period(1,  2);
        $this->scope2 = new Period(10, 20);

        $this->liveScore = new PeriodLiveScore;

        $this->liveScore->add($this->scope1);
        $this->liveScore->add($this->scope2);
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
