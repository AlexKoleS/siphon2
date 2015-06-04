<?php
namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class PlayerRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->request = new PlayerRequest(
            Sport::NFL(),
            '<season>',
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

    public function testId()
    {
        $this->assertSame(
            123,
            $this->request->teamId()
        );

        $this->request->setTeamId(456);

        $this->assertSame(
            456,
            $this->request->teamId()
        );
    }

    public function testIdWithString()
    {
        $this->request->setTeamId('/sport/football/team:123');

        $this->assertSame(
            123,
            $this->request->teamId()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->request->accept($visitor->mock());

        $visitor->visitPlayerRequest->calledWith($this->request);
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

    public function testToString()
    {
        $this->assertSame(
            'player(NFL <season> team:123)',
            strval($this->request)
        );
    }
}
