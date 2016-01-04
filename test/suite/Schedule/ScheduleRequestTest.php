<?php

namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class ScheduleRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new ScheduleRequest(
            Sport::NFL()
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

    public function testSerialize()
    {
        $buffer  = serialize($this->subject);
        $request = unserialize($buffer);

        $this->assertEquals(
            $this->subject,
            $request
        );

        // Enum instances must be identical ...
        $this->assertSame(
            Sport::NFL(),
            $request->sport()
        );

        $this->assertSame(
            ScheduleType::FULL(),
            $request->type()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitScheduleRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'schedule(NFL)',
            $this->subject->rateLimitGroup()
        );

        $this->subject->setType(ScheduleType::DELETED());

        $this->assertSame(
            'schedule(NFL)',
            $this->subject->rateLimitGroup()
        );

        $this->subject->setType(ScheduleType::LIMIT_2_DAYS());

        $this->assertSame(
            'schedule(NFL)',
            $this->subject->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'schedule(NFL)',
            strval($this->subject)
        );

        $this->subject->setType(ScheduleType::DELETED());

        $this->assertSame(
            'schedule(NFL deleted)',
            strval($this->subject)
        );

        $this->subject->setType(ScheduleType::LIMIT_2_DAYS());

        $this->assertSame(
            'schedule(NFL 2 days)',
            strval($this->subject)
        );
    }
}
