<?php
namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Statistics\StatisticsCollection;
use PHPUnit_Framework_TestCase;

class BoxScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competition = Phony::mock(CompetitionInterface::class)->mock();

        $this->homeTeamStatistics = new StatisticsCollection;
        $this->awayTeamStatistics = new StatisticsCollection;

        $this->player1 = Phony::fullMock(Player::class);
        $this->player2 = Phony::fullMock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->statistics1 = Phony::fullMock(StatisticsCollection::class)->mock();
        $this->statistics2 = Phony::fullMock(StatisticsCollection::class)->mock();

        $this->response = new BoxScoreResponse(
            $this->competition,
            $this->homeTeamStatistics,
            $this->awayTeamStatistics
        );
    }

    public function testCompetition()
    {
        $this->assertSame(
            $this->competition,
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

        $statistics = new StatisticsCollection;

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

        $statistics = new StatisticsCollection;

        $this->response->setAwayTeamStatistics($statistics);

        $this->assertSame(
            $statistics,
            $this->response->awayTeamStatistics()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->player1->mock(), $this->statistics1);

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

        $this->response->add($this->player1->mock(), $this->statistics1);

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

        $this->response->add($this->player1->mock(), $this->statistics1);
        $this->response->add($this->player2->mock(), $this->statistics2);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
                [$this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->player1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->player1->mock(), $this->statistics1);
        $this->response->add($this->player1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->player1->mock(), $this->statistics1);
        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->response->add($this->player1->mock(), $this->statistics1);
        $this->response->remove($this->player1->mock());
        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->player1->mock(), $this->statistics1);
        $this->response->add($this->player2->mock(), $this->statistics2);

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitBoxScoreResponse->calledWith($this->response);
    }
}
