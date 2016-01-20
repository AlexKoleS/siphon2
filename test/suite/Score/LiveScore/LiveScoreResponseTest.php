<?php

namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class LiveScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $competition = Phony::mock(CompetitionInterface::class);
        $competition->sport->returns(Sport::NFL());
        $this->competition = $competition->mock();

        $this->score = Phony::mock(Score::class)->mock();

        $this->subject = new LiveScoreResponse(
            $this->competition,
            $this->score
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
            $this->competition,
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

        $score = Phony::mock(Score::class)->mock();

        $this->subject->setScore($score);

        $this->assertSame(
            $score,
            $this->subject->score()
        );
    }

    public function testCurrentPeriod()
    {
        $this->assertNull(
            $this->subject->currentPeriod()
        );

        $period = new Period(PeriodType::PERIOD(), 1);

        $this->subject->setCurrentPeriod($period);

        $this->assertSame(
            $period,
            $this->subject->currentPeriod()
        );

        $this->subject->setCurrentPeriod(null);

        $this->assertNull(
            $this->subject->currentPeriod()
        );
    }

    public function testGameTime()
    {
        $this->assertNull(
            $this->subject->gameTime()
        );

        $gameTime = new Duration(123);

        $this->subject->setGameTime($gameTime);

        $this->assertSame(
            $gameTime,
            $this->subject->gameTime()
        );

        $this->subject->setGameTime(null);

        $this->assertNull(
            $this->subject->gameTime()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitLiveScoreResponse->calledWith($this->subject);
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
