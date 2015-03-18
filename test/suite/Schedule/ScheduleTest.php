<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class ScheduleTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->comp1a  = Phony::mock(CompetitionInterface::class)->mock();
        $this->comp1b  = Phony::mock(CompetitionInterface::class)->mock();
        $this->season1 = Phony::mock(SeasonInterface::class);
        $this->season1->count->returns(2);
        $this->season1->isEmpty->returns(false);
        $this->season1->competitions->returns(
            [
                $this->comp1a,
                $this->comp1b,
            ]
        );

        $this->comp2a  = Phony::mock(CompetitionInterface::class)->mock();
        $this->comp2b  = Phony::mock(CompetitionInterface::class)->mock();
        $this->season2 = Phony::mock(SeasonInterface::class);
        $this->season2->count->returns(2);
        $this->season2->isEmpty->returns(false);
        $this->season2->competitions->returns(
            [
                $this->comp2a,
                $this->comp2b,
            ]
        );

        $this->season3 = Phony::mock(SeasonInterface::class);
        $this->season3->count->returns(0);
        $this->season3->isEmpty->returns(true);
        $this->season3->competitions->returns([]);

        $this->schedule = new Schedule;
    }

    public function testAdd()
    {
        $this->schedule->add($this->season1->mock());
        $this->schedule->add($this->season2->mock());

        $this->assertSame(
            [
                $this->season1->mock(),
                $this->season2->mock(),
            ],
            $this->schedule->seasons()
        );
    }

    public function testRemove()
    {
        $this->schedule->add($this->season1->mock());
        $this->schedule->add($this->season2->mock());

        $this->schedule->remove($this->season1->mock());

        $this->assertSame(
            [
                $this->season2->mock(),
            ],
            $this->schedule->seasons()
        );
    }

    public function testRemoveWithUnknownSeason()
    {
        $this->schedule->remove($this->season1->mock());

        $this->assertSame(
            [
            ],
            $this->schedule->seasons()
        );
    }

    public function testSeasons()
    {
        $this->assertSame(
            [
            ],
            $this->schedule->seasons()
        );

        $this->schedule->add($this->season1->mock());

        $this->assertSame(
            [
                $this->season1->mock(),
            ],
            $this->schedule->seasons()
        );

        $this->schedule->add($this->season2->mock());

        $this->assertSame(
            [
                $this->season1->mock(),
                $this->season2->mock(),
            ],
            $this->schedule->seasons()
        );
    }

    public function testCompetitions()
    {
        $this->assertSame(
            [
            ],
            $this->schedule->competitions()
        );

        $this->schedule->add($this->season3->mock());

        $this->assertSame(
            [
            ],
            $this->schedule->competitions()
        );

        $this->schedule->add($this->season1->mock());

        $this->assertSame(
            [
                $this->comp1a,
                $this->comp1b,
            ],
            $this->schedule->competitions()
        );

        $this->schedule->add($this->season2->mock());

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

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->schedule->isEmpty()
        );

        $this->schedule->add($this->season3->mock());

        $this->assertTrue(
            $this->schedule->isEmpty()
        );

        $this->schedule->add($this->season1->mock());
        $this->schedule->add($this->season2->mock());

        $this->assertFalse(
            $this->schedule->isEmpty()
        );

        $this->schedule->remove($this->season1->mock());

        $this->assertFalse(
            $this->schedule->isEmpty()
        );

        $this->schedule->remove($this->season2->mock());

        $this->assertTrue(
            $this->schedule->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->schedule)
        );

        $this->schedule->add($this->season3->mock());

        $this->assertSame(
            0,
            count($this->schedule)
        );

        $this->schedule->add($this->season1->mock());
        $this->schedule->add($this->season2->mock());

        $this->assertSame(
            4,
            count($this->schedule)
        );

        $this->schedule->remove($this->season1->mock());

        $this->assertSame(
            2,
            count($this->schedule)
        );

        $this->schedule->remove($this->season2->mock());

        $this->assertSame(
            0,
            count($this->schedule)
        );
    }

    public function testGetIterator()
    {
        $this->schedule->add($this->season1->mock());
        $this->schedule->add($this->season2->mock());
        $this->schedule->add($this->season3->mock());

        $this->assertEquals(
            [
                $this->comp1a,
                $this->comp1b,
                $this->comp2a,
                $this->comp2b,
            ],
            iterator_to_array($this->schedule)
        );
    }
}
