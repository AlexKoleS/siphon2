<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Score\Score;
use PHPUnit_Framework_TestCase;

class LiveScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competition = Phony::mock(CompetitionInterface::class)->mock();
        $this->score       = Phony::fullMock(Score::class)->mock();

        $this->response = new LiveScoreResponse(
            $this->competition,
            $this->score
        );
    }

    public function testCompetition()
    {
        $this->assertSame(
            $this->competition,
            $this->response->competition()
        );

        $competition = Phony::mock(CompetitionInterface::class)->mock();

        $this->response->setCompetition($competition);

        $this->assertSame(
            $competition,
            $this->response->competition()
        );
    }

    public function testScore()
    {
        $this->assertSame(
            $this->score,
            $this->response->score()
        );

        $score = Phony::fullMock(Score::class)->mock();

        $this->response->setScore($score);

        $this->assertSame(
            $score,
            $this->response->score()
        );
    }

    public function testAccept()
    {
        $this->markTestSkipped();

        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitPlayerResponse->calledWith($this->response);
    }
}
