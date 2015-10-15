<?php

namespace Icecave\Siphon\Player;

use PHPUnit_Framework_TestCase;

class PlayerSeasonDetailsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->details = new PlayerSeasonDetails(
            '<number>',
            '<position>',
            '<position-name>',
            true
        );
    }

    public function testNumber()
    {
        $this->assertSame(
            '<number>',
            $this->details->number()
        );
    }

    public function testPosition()
    {
        $this->assertSame(
            '<position>',
            $this->details->position()
        );
    }

    public function testPositionName()
    {
        $this->assertSame(
            '<position-name>',
            $this->details->positionName()
        );
    }

    public function testIsActive()
    {
        $this->assertTrue(
            $this->details->isActive()
        );
    }
}
