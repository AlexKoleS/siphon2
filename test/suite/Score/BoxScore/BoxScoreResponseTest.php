<?php
namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use PHPUnit_Framework_TestCase;

class BoxScoreResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->competition = Phony::mock(CompetitionInterface::class)->mock();

        $this->response = new BoxScoreResponse(
            $this->competition
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

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitBoxScoreResponse->calledWith($this->response);
    }
}
