<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\PeriodInterface;
use Icecave\Siphon\Score\ScoreInterface;
use PHPUnit_Framework_TestCase;

class PeriodResultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->result = new PeriodResult;
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

    public function testCurrentGameTime()
    {
        $this->assertNull(
            $this->result->currentGameTime()
        );

        $gameTime = new Duration;

        $this->result->setCurrentGameTime($gameTime);

        $this->assertSame(
            $gameTime,
            $this->result->currentGameTime()
        );

        $this->result->setCurrentGameTime(null);

        $this->assertNull(
            $this->result->currentGameTime()
        );
    }
}
