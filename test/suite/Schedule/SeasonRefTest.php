<?php
namespace Icecave\Siphon\Schedule;

use PHPUnit_Framework_TestCase;

class SeasonRefTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = new SeasonRef(
            '<id>',
            '<name>'
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
}
