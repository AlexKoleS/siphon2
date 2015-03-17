<?php
namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class InningsScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = new Innings(1,  2,  3,  4,  5,  6);
        $this->scope2 = new Innings(10, 20, 30, 40, 50, 60);

        $this->score = new InningsScore;

        $this->score->add($this->scope1);
        $this->score->add($this->scope2);
    }

    public function testHomeTeamHits()
    {
        $this->assertSame(
            33,
            $this->score->homeTeamHits()
        );
    }

    public function testAwayTeamHits()
    {
        $this->assertSame(
            44,
            $this->score->awayTeamHits()
        );
    }

    public function testHomeTeamErrors()
    {
        $this->assertSame(
            55,
            $this->score->homeTeamErrors()
        );
    }

    public function testAwayTeamErrors()
    {
        $this->assertSame(
            66,
            $this->score->awayTeamErrors()
        );
    }

    public function testScopeClass()
    {
        $this->assertSame(
            Innings::class,
            $this->score->scopeClass()
        );
    }
}
