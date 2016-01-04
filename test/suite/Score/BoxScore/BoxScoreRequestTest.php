<?php

namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class BoxScoreRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new BoxScoreRequest(
            Sport::NFL(),
            '<season>',
            123
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

    public function testSeasonName()
    {
        $this->assertSame(
            '<season>',
            $this->subject->seasonName()
        );

        $this->subject->setSeasonName('<other>');

        $this->assertSame(
            '<other>',
            $this->subject->seasonName()
        );
    }

    public function testCompetitionId()
    {
        $this->assertSame(
            123,
            $this->subject->competitionId()
        );

        $this->subject->setCompetitionId(456);

        $this->assertSame(
            456,
            $this->subject->competitionId()
        );
    }

    public function testCompetitionIdWithString()
    {
        $this->subject->setCompetitionId('/sport/football/competition:123');

        $this->assertSame(
            123,
            $this->subject->competitionId()
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

        $visitor->visitBoxScoreRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'box-score(NFL)',
            $this->subject->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'box-score(NFL competition:123)',
            strval($this->subject)
        );
    }
}
