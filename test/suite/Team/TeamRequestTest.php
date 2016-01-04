<?php

namespace Icecave\Siphon\Team;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class TeamRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = new TeamRequest(
            Sport::NFL(),
            '<season>'
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

    public function testSeasonName()
    {
        $this->assertSame(
            '<season>',
            $this->request->seasonName()
        );

        $this->request->setSeasonName('<other>');

        $this->assertSame(
            '<other>',
            $this->request->seasonName()
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

        $visitor->visitTeamRequest->calledWith($this->request);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'team(NFL)',
            $this->request->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'team(NFL <season>)',
            strval($this->request)
        );
    }
}
