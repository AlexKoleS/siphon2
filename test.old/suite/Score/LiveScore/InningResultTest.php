<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\PeriodInterface;
use Icecave\Siphon\Score\ScoreInterface;
use PHPUnit_Framework_TestCase;

class InningResultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->result = new InningResult;
    }

    public function testCurrentScope()
    {
        $this->assertNull(
            $this->result->currentScope()
        );

        $scope = Phony::mock(PeriodInterface::class)->mock();

        $this->result->setCurrentScope($scope);

        $this->assertSame(
            $scope,
            $this->result->currentScope()
        );

        $this->result->setCurrentScope(null);

        $this->assertNull(
            $this->result->currentScope()
        );
    }

    public function testCurrentScopeStatus()
    {
        $this->assertNull(
            $this->result->currentScopeStatus()
        );

        $this->result->setCurrentScopeStatus(
            ScopeStatus::DELAY_RAIN()
        );

        $this->assertSame(
            ScopeStatus::DELAY_RAIN(),
            $this->result->currentScopeStatus()
        );

        $this->result->setCurrentScopeStatus(
            null
        );

        $this->assertNull(
            $this->result->currentScopeStatus()
        );
    }

    public function testCompetitionStatus()
    {
        $this->assertSame(
            CompetitionStatus::OTHER(),
            $this->result->competitionStatus()
        );

        $this->result->setCompetitionStatus(
            CompetitionStatus::SCHEDULED()
        );

        $this->assertSame(
            CompetitionStatus::SCHEDULED(),
            $this->result->competitionStatus()
        );
    }

    public function testCompetitionScore()
    {
        $score = Phony::mock(ScoreInterface::class)->mock();

        $this->result->setCompetitionScore($score);

        $this->assertSame(
            $score,
            $this->result->competitionScore()
        );
    }

    public function testCompetitionScoreWhenUnset()
    {
        $this->setExpectedException(
            'LogicException',
            'Score has not been set.'
        );

        $this->result->competitionScore();
    }

    public function testCurrentInningSubType()
    {
        $this->assertNull(
            $this->result->currentInningSubType()
        );

        $this->result->setCurrentInningSubType(
            InningSubType::TOP()
        );

        $this->assertSame(
            InningSubType::TOP(),
            $this->result->currentInningSubType()
        );

        $this->result->setCurrentInningSubType(null);

        $this->assertNull(
            $this->result->currentInningSubType()
        );
    }
}
