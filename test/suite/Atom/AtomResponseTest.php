<?php
namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use PHPUnit_Framework_TestCase;

class AtomResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);

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

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add('<url 1>', DateTime::fromUnixTime(1));

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

        $this->response->add('<url 1>', DateTime::fromUnixTime(1));

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

        $this->response->add('<url 1>', DateTime::fromUnixTime(1));
        $this->response->add('<url 2>', DateTime::fromUnixTime(2));

        $this->assertEquals(
            [
                [
                    '<url 1>',
                    DateTime::fromUnixTime(1),
                ],
                [
                    '<url 2>',
                    DateTime::fromUnixTime(2),
                ],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertEquals(
            [
                [
                    '<url 1>',
                    DateTime::fromUnixTime(1),
                ],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertEquals(
            [
                [
                    '<url 1>',
                    DateTime::fromUnixTime(1),
                ],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));
        $this->response->remove('<url 1>');

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));
        $this->response->remove('<url 1>');
        $this->response->remove('<url 1>');

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));
        $this->response->add('<url 1>', DateTime::fromUnixTime(1));

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitAtomResponse->calledWith($this->response);
    }
}
