<?php

namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Team\TeamInterface;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class BoxScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competition = Phony::mock(CompetitionInterface::class);
        $this->competition->sport->returns(Sport::NFL());

        $this->score = new Score();

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

        $this->subject = new BoxScoreResponse(
            $this->competition->mock(),
            $this->score,
            $this->homeTeamStatistics,
            $this->awayTeamStatistics
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->subject->sport()
        );
    }

    public function testCompetition()
    {
        $this->assertSame(
            $this->competition->mock(),
            $this->subject->competition()
        );

        $competition = Phony::mock(CompetitionInterface::class)->mock();

        $this->subject->setCompetition($competition);

        $this->assertSame(
            $competition,
            $this->subject->competition()
        );
    }

    public function testScore()
    {
        $this->assertSame(
            $this->score,
            $this->subject->score()
        );

        $score = new Score();

        $this->subject->setScore($score);

        $this->assertSame(
            $score,
            $this->subject->score()
        );
    }

    public function testHomeTeamStatistics()
    {
        $this->assertSame(
            $this->homeTeamStatistics,
            $this->subject->homeTeamStatistics()
        );

        $statistics = new StatisticsCollection();

        $this->subject->setHomeTeamStatistics($statistics);

        $this->assertSame(
            $statistics,
            $this->subject->homeTeamStatistics()
        );
    }

    public function testAwayTeamStatistics()
    {
        $this->assertSame(
            $this->awayTeamStatistics,
            $this->subject->awayTeamStatistics()
        );

        $statistics = new StatisticsCollection();

        $this->subject->setAwayTeamStatistics($statistics);

        $this->assertSame(
            $statistics,
            $this->subject->awayTeamStatistics()
        );
    }

    public function testIsFinalized()
    {
        $this->assertFalse(
            $this->subject->isFinalized()
        );

        $this->subject->setIsFinalized(true);

        $this->assertTrue(
            $this->subject->isFinalized()
        );
    }

    public function testAddWithHomeTeam()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddWithAwayTeam()
    {
        $this->subject->add(
            $this->awayTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->awayTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddWithUnknownTeam()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'The team object must be one of the ' . TeamInterface::class . ' instances from the competition object.'
        );

        $this->subject->add(
            Phony::mock(TeamInterface::class)->mock(),
            $this->player1->mock(),
            $this->statistics1
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->remove($this->player1->mock());
        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->add(
            $this->homeTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

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

        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

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

        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->add(
            $this->homeTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
                [$this->homeTeam, $this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testHomeTeamPlayers()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->add(
            $this->awayTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->homeTeam, $this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject->homeTeamPlayers())
        );
    }

    public function testAwayTeamPlayers()
    {
        $this->subject->add(
            $this->homeTeam,
            $this->player1->mock(),
            $this->statistics1
        );

        $this->subject->add(
            $this->awayTeam,
            $this->player2->mock(),
            $this->statistics2
        );

        $this->assertEquals(
            [
                [$this->awayTeam, $this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->subject->awayTeamPlayers())
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitBoxScoreResponse->calledWith($this->subject);
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
