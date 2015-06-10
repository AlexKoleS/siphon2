<?php
namespace Icecave\Siphon\Score\Period;

use PHPUnit_Framework_TestCase;

class PeriodTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->period = new Period(
            PeriodType::INNING(),
            1,
            10,
            20
        );
    }

    public function testType()
    {
        $this->assertSame(
            PeriodType::INNING(),
            $this->period->type()
        );
    }

    public function testNumber()
    {
        $this->assertSame(
            1,
            $this->period->number()
        );
    }

    public function testHomeScore()
    {
        $this->assertSame(
            10,
            $this->period->homeScore()
        );
    }

    public function testAwayScore()
    {
        $this->assertSame(
            20,
            $this->period->awayScore()
        );
    }
}
