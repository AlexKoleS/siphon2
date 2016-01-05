<?php

namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class ScheduleResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season1 = Phony::mock(Season::class);
        $this->season2 = Phony::mock(Season::class);

        $this->season1->id->returns('<season 1>');
        $this->season2->id->returns('<season 2>');

        $this->subject = new ScheduleResponse(
            Sport::NFL(),
            ScheduleType::FULL()
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->subject->sport()
        );

        $this->subject->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->subject->sport()
        );
    }

    public function testType()
    {
        $this->assertSame(
            ScheduleType::FULL(),
            $this->subject->type()
        );

        $this->subject->setType(ScheduleType::DELETED());

        $this->assertSame(
            ScheduleType::DELETED(),
            $this->subject->type()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add($this->season1->mock());

        $this->assertFalse(
            $this->subject->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->subject)
        );

        $this->subject->add($this->season1->mock());

        $this->assertSame(
            1,
            count($this->subject)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );

        $this->subject->add($this->season1->mock());
        $this->subject->add($this->season2->mock());

        $this->assertEquals(
            [
                $this->season1->mock(),
                $this->season2->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAdd()
    {
        $this->subject->add($this->season1->mock());

        $this->assertEquals(
            [
                $this->season1->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add($this->season1->mock());
        $this->subject->add($this->season1->mock());

        $this->assertEquals(
            [
                $this->season1->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add($this->season1->mock());
        $this->subject->remove($this->season1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownSeason()
    {
        $this->subject->add($this->season1->mock());
        $this->subject->remove($this->season1->mock());
        $this->subject->remove($this->season1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add($this->season1->mock());
        $this->subject->add($this->season2->mock());

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitScheduleResponse->calledWith($this->subject);
    }

    public function testModifiedTime()
    {
        $this->assertNull(
            $this->subject->modifiedTime()
        );

        $modifiedTime = DateTime::fromUnixTime(123);
        $this->subject->setModifiedTime($modifiedTime);

        $this->assertSame(
            $modifiedTime,
            $this->subject->modifiedTime()
        );

        $this->subject->setModifiedTime(null);

        $this->assertNull(
            $this->subject->modifiedTime()
        );
    }
}
