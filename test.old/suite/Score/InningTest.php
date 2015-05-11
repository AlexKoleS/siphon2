<?php
namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class InningTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Inning(1, 2);
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
