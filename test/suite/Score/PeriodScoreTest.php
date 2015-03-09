<?php
namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class PeriodScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->score = new PeriodScore;
    }

    public function testScopeClass()
    {
        $this->assertSame(
            Period::class,
            $this->score->scopeClass()
        );
    }
}
