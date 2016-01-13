<?php

namespace Icecave\Siphon\Hockey\ProbableGoalies;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class HockeyProbableGoaliesRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new HockeyProbableGoaliesRequest(Sport::NHL());
    }

    public function testSport()
    {
        $this->assertSame(Sport::NHL(), $this->subject->sport());

        $this->subject->setSport(Sport::NBA());

        $this->assertSame(Sport::NBA(), $this->subject->sport());
    }

    public function testSerialize()
    {
        $buffer  = serialize($this->subject);
        $request = unserialize($buffer);

        $this->assertEquals($this->subject, $request);

        // Enum instances must be identical ...
        $this->assertSame(Sport::NHL(), $request->sport());
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitHockeyProbableGoaliesRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame('hockey-probable-goalies(NHL)', $this->subject->rateLimitGroup());
    }

    public function testToString()
    {
        $this->assertSame('hockey-probable-goalies(NHL)', strval($this->subject));
    }
}
