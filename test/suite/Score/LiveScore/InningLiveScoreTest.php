<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\PeriodInterface;
use Icecave\Siphon\Score\ScoreInterface;
use PHPUnit_Framework_TestCase;

class InningLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->liveScore = new InningLiveScore;
    }

    public function testCurrentScope()
    {
        $this->assertNull(
            $this->liveScore->currentScope()
        );

        $scope = Phony::mock(PeriodInterface::class)->mock();

        $this->liveScore->setCurrentScope($scope);

        $this->assertSame(
            $scope,
            $this->liveScore->currentScope()
        );

        $this->liveScore->setCurrentScope(null);

        $this->assertNull(
            $this->liveScore->currentScope()
        );
    }

    public function testCompetitionStatus()
    {
        $this->assertSame(
            CompetitionStatus::OTHER(),
            $this->liveScore->competitionStatus()
        );

        $this->liveScore->setCompetitionStatus(
            CompetitionStatus::SCHEDULED()
        );

        $this->assertSame(
            CompetitionStatus::SCHEDULED(),
            $this->liveScore->competitionStatus()
        );
    }

    public function testCompetitionScore()
    {
        $score = Phony::mock(ScoreInterface::class)->mock();

        $this->liveScore->setCompetitionScore($score);

        $this->assertSame(
            $score,
            $this->liveScore->competitionScore()
        );
    }

    public function testCompetitionScoreWhenUnset()
    {
        $this->setExpectedException(
            'LogicException',
            'Score has not been set.'
        );

        $this->liveScore->competitionScore();
    }

    public function testCurrentInningSubType()
    {
        $this->assertNull(
            $this->liveScore->currentInningSubType()
        );

        $this->liveScore->setCurrentInningSubType(
            InningSubType::TOP()
        );

        $this->assertSame(
            InningSubType::TOP(),
            $this->liveScore->currentInningSubType()
        );

        $this->liveScore->setCurrentInningSubType(null);

        $this->assertNull(
            $this->liveScore->currentInningSubType()
        );
    }
}
