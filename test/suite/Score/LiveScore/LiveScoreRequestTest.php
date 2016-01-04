<?php

namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class LiveScoreRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = new LiveScoreRequest(
            Sport::NFL(),
            123
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

    public function testCompetitionId()
    {
        $this->assertSame(
            123,
            $this->request->competitionId()
        );

        $this->request->setCompetitionId(456);

        $this->assertSame(
            456,
            $this->request->competitionId()
        );
    }

    public function testCompetitionIdWithString()
    {
        $this->request->setCompetitionId('/sport/football/competition:123');

        $this->assertSame(
            123,
            $this->request->competitionId()
        );
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
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->request->accept($visitor->mock());

        $visitor->visitLiveScoreRequest->calledWith($this->request);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'live-score(NFL)',
            $this->request->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'live-score(NFL competition:123)',
            strval($this->request)
        );
    }
}
