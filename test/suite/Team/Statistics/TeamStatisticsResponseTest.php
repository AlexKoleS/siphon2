<?php
namespace Icecave\Siphon\Team\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamInterface;
use PHPUnit_Framework_TestCase;

class TeamStatisticsResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::fullMock(Season::class)->mock();

        $this->team1 = Phony::mock(TeamInterface::class);
        $this->team2 = Phony::mock(TeamInterface::class);

        $this->team1->id->returns('<team 1>');
        $this->team2->id->returns('<team 2>');

        $this->player1 = Phony::fullMock(Player::class);
        $this->player2 = Phony::fullMock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->statistics1 = Phony::fullMock(StatisticsCollection::class)->mock();
        $this->statistics2 = Phony::fullMock(StatisticsCollection::class)->mock();

        $this->response = new TeamStatisticsResponse(
            Sport::NFL(),
            $this->season,
            StatisticsType::COMBINED()
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

        $season = Phony::fullMock(Season::class)->mock();
        $this->response->setSeason($season);

        $this->assertSame(
            $season,
            $this->response->season()
        );
    }

    public function testType()
    {
        $this->assertSame(
            StatisticsType::COMBINED(),
            $this->response->type()
        );

        $this->response->setType(StatisticsType::SPLIT());

        $this->assertSame(
            StatisticsType::SPLIT(),
            $this->response->type()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->team1->mock(), $this->statistics1);

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

        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->add($this->team2->mock(), $this->statistics1);

        $this->assertSame(
            2,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->add($this->team2->mock(), $this->statistics2);

        $this->assertEquals(
            [
                [$this->team1->mock(), $this->statistics1],
                [$this->team2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->team1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->team1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->add($this->team1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->team1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->remove($this->team1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->add($this->team2->mock(), $this->statistics1);
        $this->response->remove($this->team1->mock());
        $this->response->remove($this->team1->mock());

        $this->assertEquals(
            [
                [$this->team2->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->team1->mock(), $this->statistics1);
        $this->response->add($this->team2->mock(), $this->statistics2);

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitTeamStatisticsResponse->calledWith($this->response);
    }
}
