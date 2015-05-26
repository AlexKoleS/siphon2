<?php
namespace Icecave\Siphon\Team;

use PHPUnit_Framework_TestCase;

class TeamTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->team = new Team(
            '<id>',
            '<name>',
            '<abbreviation>',
            '<nickname>'
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

    public function testNickname()
    {
        $this->assertSame(
            '<nickname>',
            $this->team->nickname()
        );
    }

    public function testAbbreviation()
    {
        $this->assertSame(
            '<abbreviation>',
            $this->team->abbreviation()
        );
    }

    public function testDisplayName()
    {
        $this->assertSame(
            '<name> <nickname>',
            $this->team->displayName()
        );
    }

    public function testDisplayNameWithoutNickname()
    {
        $this->team = new Team(
            '<id>',
            '<name>',
            '<abbreviation>',
            null
        );

        $this->assertSame(
            '<name>',
            $this->team->displayName()
        );
    }
}
