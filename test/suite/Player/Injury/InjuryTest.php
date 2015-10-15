<?php

namespace Icecave\Siphon\Player\Injury;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class InjuryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->injury = new Injury(
            '<id>',
            '<location>',
            InjuryStatus::PROBABLE(),
            '<status info>',
            '<status note>',
            Date::fromUnixTime(0),
            DateTime::fromUnixTime(1)
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->injury->id()
        );
    }

    public function testLocation()
    {
        $this->assertSame(
            '<location>',
            $this->injury->location()
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            InjuryStatus::PROBABLE(),
            $this->injury->status()
        );
    }

    public function testStatusInformation()
    {
        $this->assertSame(
            '<status info>',
            $this->injury->statusInformation()
        );
    }

    public function testStatusNote()
    {
        $this->assertSame(
            '<status note>',
            $this->injury->statusNote()
        );
    }

    public function testStartDate()
    {
        $this->assertEquals(
            Date::fromUnixTime(0),
            $this->injury->startDate()
        );
    }

    public function testUpdatedTime()
    {
        $this->assertEquals(
            DateTime::fromUnixTime(1),
            $this->injury->updatedTime()
        );
    }
}
