<?php
namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use PHPUnit_Framework_TestCase;

class AtomResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);
        $this->request1    = Phony::mock(RequestInterface::class)->mock();
        $this->request2    = Phony::mock(RequestInterface::class)->mock();

        $this->response = new AtomResponse($this->updatedTime);
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->response->updatedTime()
        );

        $updatedTime = DateTime::fromUnixTime(1);

        $this->response->setUpdatedTime($updatedTime);

        $this->assertSame(
            $updatedTime,
            $this->response->updatedTime()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->response)
        );

        $this->response->add($this->request1);

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

        $this->response->add($this->request1);
        $this->response->add($this->request2);

        $this->assertSame(
            [
                $this->request1,
                $this->request2,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->request1);

        $this->assertSame(
            [
                $this->request1,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->request1);
        $this->response->add($this->request1);

        $this->assertSame(
            [
                $this->request1,
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->request1);
        $this->response->remove($this->request1);

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->response->add($this->request1);
        $this->response->remove($this->request1);
        $this->response->remove($this->request1);

        $this->assertSame(
            [],
            iterator_to_array($this->response)
        );
    }
}
