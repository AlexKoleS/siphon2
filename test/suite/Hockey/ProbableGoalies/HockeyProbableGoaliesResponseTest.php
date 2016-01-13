<?php

namespace Icecave\Siphon\Hockey\ProbableGoalies;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionRef;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;
use Icecave\Siphon\Team\TeamRef;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class HockeyProbableGoaliesResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new HockeyProbableGoaliesResponse(Sport::NHL());

        $this->homeTeam = new TeamRef('home-team-id', 'Home Team');
        $this->awayTeam = new TeamRef('away-team-id', 'Away Team');
        $this->competition = new CompetitionRef(
            'competition-id',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2001-02-03T04:05:06Z'),
            Sport::NHL(),
            $this->homeTeam,
            $this->awayTeam
        );

        $this->homePlayer = new Player('home-player-id', 'Home', 'Player');
        $this->awayPlayer = new Player('away-player-id', 'Away', 'Player');
    }

    public function testSport()
    {
        $this->assertSame(Sport::NHL(), $this->subject->sport());

        $this->subject->setSport(Sport::NBA());

        $this->assertSame(Sport::NBA(), $this->subject->sport());
    }

    public function testAddWithHomeTeam()
    {
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);

        $this->assertSame(
            [[$this->competition, $this->homeTeam, $this->homePlayer]],
            iterator_to_array($this->subject)
        );
    }

    public function testAddWithAwayTeam()
    {
        $this->subject->add($this->competition, $this->awayTeam, $this->awayPlayer);

        $this->assertSame(
            [[$this->competition, $this->awayTeam, $this->awayPlayer]],
            iterator_to_array($this->subject)
        );
    }

    public function testAddWithUnknownTeam()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'The team object must be one of the ' . TeamInterface::class . ' instances from the competition object.'
        );

        $this->subject->add($this->competition, Phony::mock(TeamInterface::class)->mock(), $this->homePlayer);
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);

        $this->assertSame(
            [[$this->competition, $this->homeTeam, $this->homePlayer]],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->remove($this->competition, $this->homeTeam, $this->homePlayer);

        $this->assertSame([], iterator_to_array($this->subject));
    }

    public function testRemoveUndefined()
    {
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->remove($this->competition, $this->homeTeam, $this->awayPlayer);

        $this->assertSame(
            [[$this->competition, $this->homeTeam, $this->homePlayer]],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->add($this->competition, $this->awayTeam, $this->awayPlayer);
        $this->subject->clear();

        $this->assertTrue($this->subject->isEmpty());
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->subject->isEmpty());

        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);

        $this->assertFalse($this->subject->isEmpty());
    }

    public function testCount()
    {
        $this->assertSame(0, count($this->subject));

        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->add($this->competition, $this->awayTeam, $this->awayPlayer);

        $this->assertSame(2, count($this->subject));
    }

    public function testGetIterator()
    {
        $this->assertEquals([], iterator_to_array($this->subject));

        $this->subject->add($this->competition, $this->homeTeam, $this->homePlayer);
        $this->subject->add($this->competition, $this->awayTeam, $this->awayPlayer);

        $this->assertEquals(
            [
                [$this->competition, $this->homeTeam, $this->homePlayer],
                [$this->competition, $this->awayTeam, $this->awayPlayer],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitHockeyProbableGoaliesResponse->calledWith($this->subject);
    }

    public function testModifiedTime()
    {
        $this->assertNull($this->subject->modifiedTime());

        $modifiedTime = DateTime::fromUnixTime(123);
        $this->subject->setModifiedTime($modifiedTime);

        $this->assertSame($modifiedTime, $this->subject->modifiedTime());

        $this->subject->setModifiedTime(null);

        $this->assertNull($this->subject->modifiedTime());
    }
}
