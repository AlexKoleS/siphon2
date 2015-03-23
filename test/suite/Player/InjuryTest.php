<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class InjuryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->date        = Date::fromUnixtime(0);
        $this->updatedTime = DateTime::fromUnixTime(1);

        $this->injury = new Injury(
            '<player-id>',
            '<type>',
            InjuryStatus::OUT(),
            '<status-info>',
            '<note>',
            $this->date,
            $this->updatedTime
        );
    }

    public function testPlayerId()
    {
        $this->assertSame(
            '<player-id>',
            $this->injury->playerId()
        );
    }

    public function testType()
    {
        $this->assertSame(
            '<type>',
            $this->injury->type()
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            InjuryStatus::OUT(),
            $this->injury->status()
        );
    }

    public function testStatusInformation()
    {
        $this->assertSame(
            '<status-info>',
            $this->injury->statusInformation()
        );
    }

    public function testNote()
    {
        $this->assertSame(
            '<note>',
            $this->injury->note()
        );
    }

    public function testDate()
    {
        $this->assertSame(
            $this->date,
            $this->injury->date()
        );
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->injury->updatedTime()
        );
    }
}
