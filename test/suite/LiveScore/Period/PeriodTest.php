<?php
namespace Icecave\Siphon\LiveScore\Period;

use Icecave\Siphon\Score\ScopeStatus;
use PHPUnit_Framework_TestCase;

class PeriodTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Period(
            1,
            2
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            ScopeStatus::COMPLETE(),
            $this->scope->status()
        );

        $this->scope->setStatus(
            ScopeStatus::IN_PROGRESS()
        );

        $this->assertSame(
            ScopeStatus::IN_PROGRESS(),
            $this->scope->status()
        );
    }

    public function testType()
    {
        $this->assertSame(
            PeriodType::PERIOD(),
            $this->scope->type()
        );

        $this->scope->setType(
            PeriodType::SHOOTOUT()
        );

        $this->assertSame(
            PeriodType::SHOOTOUT(),
            $this->scope->type()
        );
    }

    public function testHomeTeamPoints()
    {
        $this->assertSame(
            1,
            $this->scope->homeTeamPoints()
        );
    }

    public function testAwayTeamPoints()
    {
        $this->assertSame(
            2,
            $this->scope->awayTeamPoints()
        );
    }
}
