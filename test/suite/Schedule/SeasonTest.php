<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use PHPUnit_Framework_TestCase;

class SeasonTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->comp1     = Phony::mock(CompetitionInterface::class)->mock();
        $this->comp2     = Phony::mock(CompetitionInterface::class)->mock();
        $this->startDate = Date::fromUnixTime(0);
        $this->endDate   = Date::fromUnixTime(1);

        $this->season = new Season(
            '<id>',
            '<name>',
            $this->startDate,
            $this->endDate
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->season->id()
        );
    }

    public function testName()
    {
        $this->assertSame(
            '<name>',
            $this->season->name()
        );
    }

    public function testStartDate()
    {
        $this->assertSame(
            $this->startDate,
            $this->season->startDate()
        );
    }

    public function testEndDate()
    {
        $this->assertSame(
            $this->endDate,
            $this->season->endDate()
        );
    }

    public function testAdd()
    {
        $this->season->add($this->comp1);
        $this->season->add($this->comp2);

        $this->assertSame(
            [
                $this->comp1,
                $this->comp2,
            ],
            $this->season->competitions()
        );
    }

    public function testRemove()
    {
        $this->season->add($this->comp1);
        $this->season->add($this->comp2);

        $this->season->remove($this->comp1);

        $this->assertSame(
            [
                $this->comp2,
            ],
            $this->season->competitions()
        );
    }

    public function testRemoveWithUnknownCompetition()
    {
        $this->season->remove($this->comp1);

        $this->assertTrue(
            $this->season->isEmpty()
        );
    }

    public function testCompetitions()
    {
        $this->assertSame(
            [
            ],
            $this->season->competitions()
        );

        $this->season->add($this->comp1);

        $this->assertSame(
            [
                $this->comp1,
            ],
            $this->season->competitions()
        );

        $this->season->add($this->comp2);

        $this->assertSame(
            [
                $this->comp1,
                $this->comp2,
            ],
            $this->season->competitions()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->season->isEmpty()
        );

        $this->season->add($this->comp1);
        $this->season->add($this->comp2);

        $this->assertFalse(
            $this->season->isEmpty()
        );

        $this->season->remove($this->comp1);

        $this->assertFalse(
            $this->season->isEmpty()
        );

        $this->season->remove($this->comp2);

        $this->assertTrue(
            $this->season->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->season)
        );

        $this->season->add($this->comp1);
        $this->season->add($this->comp2);

        $this->assertSame(
            2,
            count($this->season)
        );

        $this->season->remove($this->comp1);

        $this->assertSame(
            1,
            count($this->season)
        );

        $this->season->remove($this->comp2);

        $this->assertSame(
            0,
            count($this->season)
        );
    }

    public function testGetIterator()
    {
        $this->season->add($this->comp1);
        $this->season->add($this->comp2);

        $this->assertEquals(
            [
                $this->comp1,
                $this->comp2,
            ],
            iterator_to_array($this->season)
        );
    }
}
