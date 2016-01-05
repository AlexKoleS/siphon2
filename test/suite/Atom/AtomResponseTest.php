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
        $this->updatedTime = 'a';
        $this->subject = new AtomResponse($this->updatedTime);
    }

    public function testUpdatedTime()
    {
        $this->assertSame($this->updatedTime, $this->subject->updatedTime());

        $updatedTime = 'b';
        $this->subject->setUpdatedTime($updatedTime);

        $this->assertSame($updatedTime, $this->subject->updatedTime());
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertFalse(
            $this->subject->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->subject)
        );

        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertSame(
            1,
            count($this->subject)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );

        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));
        $this->subject->add('<url 2>', DateTime::fromUnixTime(2));

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
            iterator_to_array($this->subject)
        );
    }

    public function testAdd()
    {
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertEquals(
            [
                [
                    '<url 1>',
                    DateTime::fromUnixTime(1),
                ],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));

        $this->assertEquals(
            [
                [
                    '<url 1>',
                    DateTime::fromUnixTime(1),
                ],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));
        $this->subject->remove('<url 1>');

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownRequest()
    {
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));
        $this->subject->remove('<url 1>');
        $this->subject->remove('<url 1>');

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));
        $this->subject->add('<url 1>', DateTime::fromUnixTime(1));

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitAtomResponse->calledWith($this->subject);
    }

    public function testModifiedTime()
    {
        $this->assertNull(
            $this->subject->modifiedTime()
        );

        $modifiedTime = DateTime::fromUnixTime(123);
        $this->subject->setModifiedTime($modifiedTime);

        $this->assertSame(
            $modifiedTime,
            $this->subject->modifiedTime()
        );

        $this->subject->setModifiedTime(null);

        $this->assertNull(
            $this->subject->modifiedTime()
        );
    }
}
