<?php

namespace Icecave\Siphon\Player\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamInterface;
use PHPUnit_Framework_TestCase;

class PlayerStatisticsResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::mock(Season::class)->mock();
        $this->team   = Phony::mock(TeamInterface::class)->mock();

        $this->player1 = Phony::mock(Player::class);
        $this->player2 = Phony::mock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->statistics1 = Phony::mock(StatisticsCollection::class)->mock();
        $this->statistics2 = Phony::mock(StatisticsCollection::class)->mock();

        $this->subject = new PlayerStatisticsResponse(
            Sport::NFL(),
            $this->season,
            $this->team,
            StatisticsType::COMBINED()
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

    public function testTeam()
    {
        $this->assertSame(
            $this->team,
            $this->subject->team()
        );

        $team = Phony::mock(TeamInterface::class)->mock();
        $this->subject->setTeam($team);

        $this->assertSame(
            $team,
            $this->subject->team()
        );
    }

    public function testType()
    {
        $this->assertSame(
            StatisticsType::COMBINED(),
            $this->subject->type()
        );

        $this->subject->setType(StatisticsType::SPLIT());

        $this->assertSame(
            StatisticsType::SPLIT(),
            $this->subject->type()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add($this->player1->mock(), $this->statistics1);

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

        $this->subject->add($this->player1->mock(), $this->statistics1);

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

        $this->subject->add($this->player1->mock(), $this->statistics1);
        $this->subject->add($this->player2->mock(), $this->statistics2);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
                [$this->player2->mock(), $this->statistics2],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAdd()
    {
        $this->subject->add($this->player1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add($this->player1->mock(), $this->statistics1);
        $this->subject->add($this->player1->mock(), $this->statistics1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->statistics1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add($this->player1->mock(), $this->statistics1);
        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->subject->add($this->player1->mock(), $this->statistics1);
        $this->subject->remove($this->player1->mock());
        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add($this->player1->mock(), $this->statistics1);
        $this->subject->add($this->player2->mock(), $this->statistics2);

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitPlayerStatisticsResponse->calledWith($this->subject);
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
