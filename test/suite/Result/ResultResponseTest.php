<?php

namespace Icecave\Siphon\Result;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Schedule\CompetitionInterface;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class ResultResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season = Phony::mock(Season::class)->mock();

        $this->competition1 = Phony::mock(CompetitionInterface::class);
        $this->competition2 = Phony::mock(CompetitionInterface::class);

        $this->competition1->id->returns('<team 1>');
        $this->competition2->id->returns('<team 2>');

        $this->response = new ResultResponse(
            Sport::NFL(),
            $this->season
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->response->sport()
        );

        $this->response->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->response->sport()
        );
    }

    public function testSeason()
    {
        $this->assertSame(
            $this->season,
            $this->response->season()
        );

        $season = Phony::mock(Season::class)->mock();
        $this->response->setSeason($season);

        $this->assertSame(
            $season,
            $this->response->season()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->competition1->mock(), true);

        $this->assertFalse(
            $this->response->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->response)
        );

        $this->response->add($this->competition1->mock(), true);

        $this->assertSame(
            1,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add($this->competition1->mock(), true);
        $this->response->add($this->competition2->mock(), false);

        $this->assertEquals(
            [
                [$this->competition1->mock(), true],
                [$this->competition2->mock(), false],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->competition1->mock(), true);

        $this->assertEquals(
            [
                [$this->competition1->mock(), true],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->competition1->mock(), false);
        $this->response->add($this->competition1->mock(), true);

        $this->assertEquals(
            [
                [$this->competition1->mock(), true],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->competition1->mock(), true);
        $this->response->remove($this->competition1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownCompetition()
    {
        $this->response->add($this->competition1->mock(), true);
        $this->response->remove($this->competition1->mock());
        $this->response->remove($this->competition1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->competition1->mock(), true);
        $this->response->add($this->competition2->mock(), false);

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitResultResponse->calledWith($this->response);
    }
}
