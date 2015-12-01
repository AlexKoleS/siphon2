<?php

namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Team\TeamInterface;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class BoxScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competition = Phony::mock(CompetitionInterface::class);
        $this->homeTeam = Phony::mock(TeamInterface::class)->mock();
        $this->awayTeam = Phony::mock(TeamInterface::class)->mock();

        $this->competition->homeTeam->returns($this->homeTeam);
        $this->competition->awayTeam->returns($this->awayTeam);

        $this->homeTeamStatistics = new StatisticsCollection();
        $this->awayTeamStatistics = new StatisticsCollection();

        $this->player1 = Phony::mock(Player::class);
        $this->player2 = Phony::mock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->statistics1 = Phony::mock(StatisticsCollection::class)->mock();
        $this->statistics2 = Phony::mock(StatisticsCollection::class)->mock();

        $this->response = new BoxScoreResponse(
            $this->competition->mock(),
            $this->homeTeamStatistics,
            $this->awayTeamStatistics
        );
    }

    public function testCompetition()
    {
        $this->assertSame(
            $this->competition->mock(),
            $this->response->competition()
        );

        $competition = Phony::mock(CompetitionInterface::class)->mock();

        $this->response->setCompetition($competition);

        $this->assertSame(
            $competition,
            $this->response->competition()
        );
    }

    public function testHomeTeamStatistics()
    {
        $this->assertSame(
            $this->homeTeamStatistics,
            $this->response->homeTeamStatistics()
        );

        $statistics = new StatisticsCollection();

        $this->response->setHomeTeamStatistics($statistics);

        $this->assertSame(
            $statistics,
            $this->response->homeTeamStatistics()
        );
    }

    public function testAwayTeamStatistics()
    {
        $this->assertSame(
            $this->awayTeamStatistics,
            $this->response->awayTeamStatistics()
        );

        $statistics = new StatisticsCollection();

        $this->response->setAwayTeamStatistics($statistics);

        $this->assertSame(
            $statistics,
            $this->response->awayTeamStatistics()
        );
    }

    public function testIsFinalized()
    {
        $this->assertFalse(
            $this->response->isFinalized()
        );

        $this->response->setIsFinalized(true);

        $this->assertTrue(
            $this->response->isFinalized()
        );
    }

    public function testAddWithHomeTeam()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddWithAwayTeam()
    {
        $this->response->add(
            $this->awayTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->awayTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddWithUnknownTeam()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'The team object must be one of the ' . TeamInterface::class . ' instances from the competition object.'
        );

        $this->response->add(
            Phony::mock(TeamInterface::class)->mock(),
            $this->player1->mock(),
            $this->statistics1
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->remove($this->player1->mock());
        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->add(
            $this->homeTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

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

        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertSame(
            1,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->add(
            $this->homeTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
                [$this->homeTeam, $this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testHomeTeamPlayers()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->add(
            $this->awayTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response->homeTeamPlayers())
        );
    }

    public function testAwayTeamPlayers()
    {
        $this->response->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->response->add(
            $this->awayTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->awayTeam, $this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->response->awayTeamPlayers())
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitBoxScoreResponse->calledWith($this->response);
    }
}
