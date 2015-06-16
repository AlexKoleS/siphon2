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
        $this->request = new ScheduleRequest(
            Sport::NFL()
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->request->sport()
        );

        $this->request->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->request->sport()
        );
    }

    public function testType()
    {
        $this->assertSame(
            ScheduleType::FULL(),
            $this->request->type()
        );

        $this->request->setType(ScheduleType::DELETED());

        $this->assertSame(
            ScheduleType::DELETED(),
            $this->request->type()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->request->accept($visitor->mock());

        $visitor->visitScheduleRequest->calledWith($this->request);
    }

    public function testSerialize()
    {
        $buffer  = serialize($this->request);
        $request = unserialize($buffer);

        $this->assertEquals(
            $this->request,
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

    public function testToString()
    {
        $this->assertSame(
            'schedule(NFL)',
            strval($this->request)
        );

        $this->request->setType(ScheduleType::DELETED());

        $this->assertSame(
            'schedule(NFL deleted)',
            strval($this->request)
        );

        $this->request->setType(ScheduleType::LIMIT_2_DAYS());

        $this->assertSame(
            'schedule(NFL 2 days)',
            strval($this->request)
        );
    }
}
