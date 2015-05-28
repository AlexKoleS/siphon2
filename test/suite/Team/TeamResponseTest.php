<?php
namespace Icecave\Siphon\Team;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\SeasonInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class TeamResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::mock(SeasonInterface::class)->mock();

        $this->team1 = Phony::mock(TeamInterface::class);
        $this->team2 = Phony::mock(TeamInterface::class);

        $this->team1->id->returns('<team 1>');
        $this->team2->id->returns('<team 2>');

        $this->response = new TeamResponse(
            Sport::NFL(),
            $this->season
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->response->sport()
        );

        $this->response->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->response->sport()
        );
    }

    public function testSeason()
    {
        $this->assertSame(
            $this->season,
            $this->response->season()
        );

        $season = Phony::mock(SeasonInterface::class)->mock();
        $this->response->setSeason($season);

        $this->assertSame(
            $season,
            $this->response->season()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->team1->mock());

        $this->assertFalse(
            $this->response->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->response)
        );

        $this->response->add($this->team1->mock());

        $this->assertSame(
            1,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add($this->team1->mock());
        $this->response->add($this->team2->mock());

        $this->assertSame(
            [
                $this->team1->mock(),
                $this->team2->mock(),
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->team1->mock());

        $this->assertSame(
            [
                $this->team1->mock(),
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->team1->mock());
        $this->response->add($this->team1->mock());

        $this->assertSame(
            [
                $this->team1->mock(),
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->team1->mock());
        $this->response->remove($this->team1->mock());

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->response->add($this->team1->mock());
        $this->response->remove($this->team1->mock());
        $this->response->remove($this->team1->mock());

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->team1->mock());
        $this->response->add($this->team2->mock());

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitTeamResponse->calledWith($this->response);
    }
}
