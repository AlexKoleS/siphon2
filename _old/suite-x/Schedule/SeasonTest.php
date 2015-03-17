<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use PHPUnit_Framework_TestCase;

class SeasonTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->startDate = Date::fromUnixTime(0);
        $this->endDate   = Date::fromUnixTime(1);

        $this->season = new Season(
            '<id>',
            '<name>',
            $this->startDate,
            $this->endDate
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->season->id()
        );
    }

    public function testName()
    {
        $this->assertSame(
            '<name>',
            $this->season->name()
        );
    }

    public function testStartDate()
    {
        $this->assertSame(
            $this->startDate,
            $this->season->startDate()
        );
    }

    public function testEndDate()
    {
        $this->assertSame(
            $this->endDate,
            $this->season->endDate()
        );
    }

    public function testCompetitions()
    {
        $comp1 = Phony::fullMock(Competition::class)->mock();
        $comp2 = Phony::fullMock(Competition::class)->mock();

        $this->assertSame(
            [],
            $this->season->competitions()
        );

        $this->season->add($comp1);
        $this->season->add($comp2);

        $this->assertSame(
            [$comp1, $comp2],
            $this->season->competitions()
        );
    }
}
