<?php
namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\SeasonInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;
use PHPUnit_Framework_TestCase;

class PlayerResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::mock(SeasonInterface::class)->mock();
        $this->team   = Phony::mock(TeamInterface::class)->mock();

        $this->player1 = Phony::fullMock(Player::class);
        $this->player2 = Phony::fullMock(Player::class);

        $this->player1->id->returns('<team 1>');
        $this->player2->id->returns('<team 2>');

        $this->seasonDetails = Phony::fullMock(PlayerSeasonDetails::class)->mock();

        $this->response = new PlayerResponse(
            Sport::NFL(),
            $this->season,
            $this->team
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

    public function testTeam()
    {
        $this->assertSame(
            $this->team,
            $this->response->team()
        );

        $team = Phony::mock(TeamInterface::class)->mock();
        $this->response->setTeam($team);

        $this->assertSame(
            $team,
            $this->response->team()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->player1->mock());

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

        $this->response->add($this->player1->mock());

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

        $this->response->add($this->player1->mock());
        $this->response->add($this->player2->mock());

        $this->assertSame(
            [
                $this->player1->mock(),
                $this->player2->mock(),
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->player1->mock());

        $this->assertSame(
            [
                $this->player1->mock(),
            ],
            iterator_to_array($this->response)
        );

        $this->assertNull(
            $this->response->findSeasonDetails(
                $this->player1->mock()
            )
        );
    }

    public function testAddWithSeasonDetails()
    {
        $this->response->add(
            $this->player1->mock(),
            $this->seasonDetails
        );

        $this->assertSame(
            $this->seasonDetails,
            $this->response->findSeasonDetails(
                $this->player1->mock()
            )
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->player1->mock());
        $this->response->add($this->player1->mock());

        $this->assertSame(
            [
                $this->player1->mock(),
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->player1->mock(), $this->seasonDetails);
        $this->response->remove($this->player1->mock());

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );

        $this->assertNull(
            $this->response->findSeasonDetails(
                $this->player1->mock()
            )
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->response->add($this->player1->mock());
        $this->response->remove($this->player1->mock());
        $this->response->remove($this->player1->mock());

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->player1->mock(), $this->seasonDetails);
        $this->response->add($this->player2->mock());

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->assertNull(
            $this->response->findSeasonDetails(
                $this->player1->mock()
            )
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitPlayerResponse->calledWith($this->response);
    }
}
