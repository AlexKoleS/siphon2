<?php

namespace Icecave\Siphon\Player\Injury;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class InjuryRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new InjuryRequest(
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
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitInjuryRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'injury(NFL)',
            $this->subject->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'injury(NFL)',
            strval($this->subject)
        );
    }
}
