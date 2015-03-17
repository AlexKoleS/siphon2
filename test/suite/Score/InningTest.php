<?php
namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class InningTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope = new Inning(
            1,
            2,
            3,
            4,
            5,
            6
        );
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
