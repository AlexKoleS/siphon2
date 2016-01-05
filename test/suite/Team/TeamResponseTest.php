<?php

namespace Icecave\Siphon\Team;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class TeamResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::mock(Season::class)->mock();

        $this->team1 = Phony::mock(TeamInterface::class);
        $this->team2 = Phony::mock(TeamInterface::class);

        $this->team1->id->returns('<team 1>');
        $this->team2->id->returns('<team 2>');

        $this->subject = new TeamResponse(
            Sport::NFL(),
            $this->season
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

    public function testSeason()
    {
        $this->assertSame(
            $this->season,
            $this->subject->season()
        );

        $season = Phony::mock(Season::class)->mock();
        $this->subject->setSeason($season);

        $this->assertSame(
            $season,
            $this->subject->season()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add($this->team1->mock());

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

        $this->subject->add($this->team1->mock());

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

        $this->subject->add($this->team1->mock());
        $this->subject->add($this->team2->mock());

        $this->assertEquals(
            [
                $this->team1->mock(),
                $this->team2->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAdd()
    {
        $this->subject->add($this->team1->mock());

        $this->assertEquals(
            [
                $this->team1->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add($this->team1->mock());
        $this->subject->add($this->team1->mock());

        $this->assertEquals(
            [
                $this->team1->mock(),
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add($this->team1->mock());
        $this->subject->remove($this->team1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownTeam()
    {
        $this->subject->add($this->team1->mock());
        $this->subject->remove($this->team1->mock());
        $this->subject->remove($this->team1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add($this->team1->mock());
        $this->subject->add($this->team2->mock());

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitTeamResponse->calledWith($this->subject);
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
