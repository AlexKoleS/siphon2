<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class CompetitionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->startTime = DateTime::fromUnixTime(0);

        $this->competition = new Competition(
            '<id>',
            CompetitionStatus::IN_PROGRESS(),
            $this->startTime,
            '<home>',
            '<away>'
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

    public function testHomeTeamId()
    {
        $this->assertSame(
            '<home>',
            $this->competition->homeTeamId()
        );
    }

    public function testAwayTeamId()
    {
        $this->assertSame(
            '<away>',
            $this->competition->awayTeamId()
        );
    }
}
