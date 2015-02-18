<?php
namespace Icecave\Siphon\LiveScore;

use PHPUnit_Framework_TestCase;

class ScopeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Scope(
            ScopeType::INNINGS(),
            ScopeStatus::COMPLETE(),
            10,
            20
        );
    }

    public function testType()
    {
        $this->assertSame(
            ScopeType::INNINGS(),
            $this->scope->type()
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            ScopeStatus::COMPLETE(),
            $this->scope->status()
        );
    }

    public function testHomePoints()
    {
        $this->assertSame(
            10,
            $this->scope->homePoints()
        );
    }

    public function testAwayPoints()
    {
        $this->assertSame(
            20,
            $this->scope->awayPoints()
        );
    }
}
