<?php
namespace Icecave\Siphon\LiveScore\Innings;

use Icecave\Siphon\Score\ScopeStatus;
use PHPUnit_Framework_TestCase;

class InningsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Innings(
            1,
            2,
            3,
            4,
            5,
            6
        );
    }

    public function testStatus()
    {
        $this->assertSame(
            ScopeStatus::COMPLETE(),
            $this->scope->status()
        );

        $this->scope->setStatus(
            ScopeStatus::IN_PROGRESS()
        );

        $this->assertSame(
            ScopeStatus::IN_PROGRESS(),
            $this->scope->status()
        );
    }

    public function testHomeTeamPoints()
    {
        $this->assertSame(
            1,
            $this->scope->homeTeamPoints()
        );
    }

    public function testAwayTeamPoints()
    {
        $this->assertSame(
            2,
            $this->scope->awayTeamPoints()
        );
    }

    public function testHomeTeamHits()
    {
        $this->assertSame(
            3,
            $this->scope->homeTeamHits()
        );
    }

    public function testAwayTeamHits()
    {
        $this->assertSame(
            4,
            $this->scope->awayTeamHits()
        );
    }

    public function testHomeTeamErrors()
    {
        $this->assertSame(
            5,
            $this->scope->homeTeamErrors()
        );
    }

    public function testAwayTeamErrors()
    {
        $this->assertSame(
            6,
            $this->scope->awayTeamErrors()
        );
    }
}
