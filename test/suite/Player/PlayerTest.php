<?php

namespace Icecave\Siphon\Player;

use PHPUnit_Framework_TestCase;

class PlayerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->player = new Player(
            '<id>',
            '<first>',
            '<last>'
        );
    }

    public function testId()
    {
        $this->assertSame(
            '<id>',
            $this->player->id()
        );
    }

    public function testFirstName()
    {
        $this->assertSame(
            '<first>',
            $this->player->firstName()
        );
    }

    public function testLastName()
    {
        $this->assertSame(
            '<last>',
            $this->player->lastName()
        );
    }

    public function testDisplayName()
    {
        $this->assertSame(
            '<first> <last>',
            $this->player->displayName()
        );
    }

    public function testDisplayNameWithEmptyLastName()
    {
        $this->player = new Player(
            '<id>',
            '<first>',
            null
        );

        $this->assertSame(
            '<first>',
            $this->player->displayName()
        );
    }
}
