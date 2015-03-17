<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\PeriodInterface;
use Icecave\Siphon\Score\ScoreInterface;
use PHPUnit_Framework_TestCase;

class PeriodLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->liveScore = new PeriodLiveScore;
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

    public function testCurrentGameTime()
    {
        $this->assertNull(
            $this->liveScore->currentGameTime()
        );

        $gameTime = new Duration;

        $this->liveScore->setCurrentGameTime($gameTime);

        $this->assertSame(
            $gameTime,
            $this->liveScore->currentGameTime()
        );

        $this->liveScore->setCurrentGameTime(null);

        $this->assertNull(
            $this->liveScore->currentGameTime()
        );
    }
}
