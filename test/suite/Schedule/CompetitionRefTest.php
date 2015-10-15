<?php

namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamInterface;
use PHPUnit_Framework_TestCase;

class CompetitionRefTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->player1 = Phony::fullMock(Player::class);
        $this->player2 = Phony::fullMock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->startTime = DateTime::fromUnixTime(0);
        $this->homeTeam  = Phony::mock(TeamInterface::class)->mock();
        $this->awayTeam  = Phony::mock(TeamInterface::class)->mock();

        $this->competition = new CompetitionRef(
            '<id>',
            CompetitionStatus::IN_PROGRESS(),
            $this->startTime,
            Sport::NFL(),
            $this->homeTeam,
            $this->awayTeam
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->competition->id()
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            CompetitionStatus::IN_PROGRESS(),
            $this->competition->status()
        );
    }

    public function testStartTime()
    {
        $this->assertSame(
            $this->startTime,
            $this->competition->startTime()
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->competition->sport()
        );
    }

    public function testHomeTeam()
    {
        $this->assertSame(
            $this->homeTeam,
            $this->competition->homeTeam()
        );
    }

    public function testAwayTeam()
    {
        $this->assertSame(
            $this->awayTeam,
            $this->competition->awayTeam()
        );
    }
}
