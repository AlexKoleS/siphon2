<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class ScheduleResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->season1 = Phony::fullMock(Season::class)->mock();
        $this->season2 = Phony::fullMock(Season::class)->mock();

        $this->response = new ScheduleResponse(
            Sport::NFL(),
            ScheduleType::FULL()
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

    public function testType()
    {
        $this->assertSame(
            ScheduleType::FULL(),
            $this->response->type()
        );

        $this->response->setType(ScheduleType::DELETED());

        $this->assertSame(
            ScheduleType::DELETED(),
            $this->response->type()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->response)
        );

        $this->response->add($this->season1);

        $this->assertSame(
            1,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add($this->season1);
        $this->response->add($this->season2);

        $this->assertSame(
            [
                $this->season1,
                $this->season2,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->season1);

        $this->assertSame(
            [
                $this->season1,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->season1);
        $this->response->add($this->season1);

        $this->assertSame(
            [
                $this->season1,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->season1);
        $this->response->remove($this->season1);

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->response->add($this->season1);
        $this->response->remove($this->season1);
        $this->response->remove($this->season1);

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }
}
