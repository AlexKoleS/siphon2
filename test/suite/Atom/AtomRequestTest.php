<?php

namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use PHPUnit_Framework_TestCase;

class AtomRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(123);
        $this->subject = new AtomRequest($this->updatedTime);
    }

    public function testUpdatedTime()
    {
        $this->assertSame($this->updatedTime->isoString(), $this->subject->updatedTime());

        $updatedTime = DateTime::fromUnixTime(1);

        $this->subject->setUpdatedTime($updatedTime);

        $this->assertSame($updatedTime->isoString(), $this->subject->updatedTime());

        $this->subject->setUpdatedTime('a');

        $this->assertSame('a', $this->subject->updatedTime());
    }

    public function testFeed()
    {
        $this->assertNull(
            $this->subject->feed()
        );

        $this->subject->setFeed('/foo/bar');

        $this->assertSame(
            '/foo/bar',
            $this->subject->feed()
        );

        $this->subject->setFeed(null);

        $this->assertNull(
            $this->subject->feed()
        );
    }

    public function testLimit()
    {
        $this->assertSame(
            5000,
            $this->subject->limit()
        );

        $this->subject->setLimit(1000);

        $this->assertSame(
            1000,
            $this->subject->limit()
        );
    }

    public function testLimitWithInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Limit must be a positive integer.'
        );

        $this->subject->setLimit(-1);
    }

    public function testOrder()
    {
        $this->assertSame(
            SORT_ASC,
            $this->subject->order()
        );

        $this->subject->setOrder(SORT_DESC);

        $this->assertSame(
            SORT_DESC,
            $this->subject->order()
        );
    }

    public function testOrderWithInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Sort order must be SORT_ASC or SORT_DESC.'
        );

        $this->subject->setOrder('<invalid>');
    }

    public function testSerialize()
    {
        $buffer  = serialize($this->subject);
        $request = unserialize($buffer);

        $this->assertEquals(
            $this->subject,
            $request
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitAtomRequest->calledWith($this->subject);
    }

    public function testRateLimitGroup()
    {
        $this->assertSame(
            'atom',
            $this->subject->rateLimitGroup()
        );

        $this->subject = new AtomRequest(
            $this->updatedTime,
            '/foo'
        );

        $this->assertSame(
            'atom(/foo)',
            $this->subject->rateLimitGroup()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'atom(1970-01-01T00:02:03+00:00 limit:5000 asc)',
            strval($this->subject)
        );

        $this->subject = new AtomRequest(
            $this->updatedTime,
            '/foo',
            1234,
            SORT_DESC
        );

        $this->assertSame(
            'atom(1970-01-01T00:02:03+00:00 feed:/foo limit:1234 desc)',
            strval($this->subject)
        );
    }
}
