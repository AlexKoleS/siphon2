<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use PHPUnit_Framework_TestCase;

class ScheduleTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->comp1a = Phony::fullMock(Competition::class)->mock();
        $this->comp1b = Phony::fullMock(Competition::class)->mock();
        $this->season1 = new Season(1, 'one', Date::fromUnixTime(0), Date::fromUnixTime(0));
        $this->season1->add($this->comp1a);
        $this->season1->add($this->comp1b);

        $this->comp2a = Phony::fullMock(Competition::class)->mock();
        $this->comp2b = Phony::fullMock(Competition::class)->mock();
        $this->season2 = new Season(2, 'two', Date::fromUnixTime(0), Date::fromUnixTime(0));
        $this->season2->add($this->comp2a);
        $this->season2->add($this->comp2b);

        $this->schedule = new Schedule;
    }

    public function testSeasons()
    {
        $this->assertSame(
            [],
            $this->schedule->seasons()
        );

        $this->schedule->add($this->season1);
        $this->schedule->add($this->season2);

        $this->assertSame(
            [$this->season1, $this->season2],
            $this->schedule->seasons()
        );
    }

    public function testCompetitions()
    {
        $this->assertSame(
            [],
            $this->schedule->competitions()
        );

        $this->schedule->add($this->season1);
        $this->schedule->add($this->season2);

        $this->assertSame(
            [
                $this->comp1a,
                $this->comp1b,
                $this->comp2a,
                $this->comp2b,
            ],
            $this->schedule->competitions()
        );
    }
}
