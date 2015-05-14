<?php
namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Player\StatisticsInterface;
use Icecave\Siphon\Score\ScoreInterface;
use PHPUnit_Framework_TestCase;

class ResultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->result = new Result;
    }

    public function testPlayerStatistics()
    {
        $this->assertSame(
            [],
            $this->result->playerStatistics()
        );

        $stats = [
            Phony::mock(StatisticsInterface::class)->mock(),
        ];

        $this->result->setPlayerStatistics($stats);

        $this->assertSame(
            $stats,
            $this->result->playerStatistics()
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

    public function testCompetitionScoreFailure()
    {
        $this->setExpectedException(
            'LogicException',
            'Score has not been set.'
        );

        $this->result->competitionScore();
    }
}
