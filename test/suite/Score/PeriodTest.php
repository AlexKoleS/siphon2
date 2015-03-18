<?php
namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class PeriodTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Period(
            PeriodType::OVERTIME(),
            1,
            2
        );
    }

    public function testType()
    {
        $this->assertSame(
            PeriodType::OVERTIME(),
            $this->scope->type()
        );
    }

    public function testHomeTeamScore()
    {
        $this->assertSame(
            1,
            $this->scope->homeTeamScore()
        );
    }

    public function testAwayTeamScore()
    {
        $this->assertSame(
            2,
            $this->scope->awayTeamScore()
        );
    }
}
