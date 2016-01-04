<?php

namespace Icecave\Siphon\Player\Image;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class ImageRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new ImageRequest(
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

    public function testTeamId()
    {
        $this->assertSame(
            123,
            $this->subject->teamId()
        );

        $this->subject->setTeamId(456);

        $this->assertSame(
            456,
            $this->subject->teamId()
        );
    }

    public function testTeamIdWithString()
    {
        $this->subject->setTeamId('/sport/football/team:123');

        $this->assertSame(
            123,
            $this->subject->teamId()
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

        $visitor->visitImageRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'image(NFL)',
            $this->subject->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'image(NFL <season> team:123)',
            strval($this->subject)
        );
    }
}
