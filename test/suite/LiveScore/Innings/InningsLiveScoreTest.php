<?php
namespace Icecave\Siphon\LiveScore\Innings;

use PHPUnit_Framework_TestCase;

class InningsLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = new Innings(1,  2,  3,  4,  5,  6);
        $this->scope2 = new Innings(10, 20, 30, 40, 50, 60);

        $this->liveScore = new InningsLiveScore;

        $this->liveScore->add($this->scope1);
        $this->liveScore->add($this->scope2);
    }

    public function testHomeTeamHits()
    {
        $this->assertSame(
            33,
            $this->liveScore->homeTeamHits()
        );
    }

    public function testAwayTeamHits()
    {
        $this->assertSame(
            44,
            $this->liveScore->awayTeamHits()
        );
    }

    public function testHomeTeamErrors()
    {
        $this->assertSame(
            55,
            $this->liveScore->homeTeamErrors()
        );
    }

    public function testAwayTeamErrors()
    {
        $this->assertSame(
            66,
            $this->liveScore->awayTeamErrors()
        );
    }

    public function testCurrentInningsType()
    {
        $this->assertNull(
            $this->liveScore->currentInningsType()
        );

        $this->liveScore->setCurrentInningsType(
            InningsType::TOP()
        );

        $this->assertSame(
            InningsType::TOP(),
            $this->liveScore->currentInningsType()
        );

        $this->liveScore->setCurrentInningsType(
            null
        );

        $this->assertNull(
            $this->liveScore->currentInningsType()
        );
    }
}
