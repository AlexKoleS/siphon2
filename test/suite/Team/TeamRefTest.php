<?php

namespace Icecave\Siphon\Team;

use PHPUnit_Framework_TestCase;

class TeamRefTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->team = new TeamRef(
            '<id>',
            '<name>'
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->team->id()
        );
    }

    public function testName()
    {
        $this->assertSame(
            '<name>',
            $this->team->name()
        );
    }
}
