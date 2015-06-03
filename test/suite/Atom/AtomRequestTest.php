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

        $this->request = new AtomRequest($this->updatedTime);
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->request->updatedTime()
        );

        $updatedTime = DateTime::fromUnixTime(1);

        $this->request->setUpdatedTime($updatedTime);

        $this->assertSame(
            $updatedTime,
            $this->request->updatedTime()
        );
    }

    public function testFeed()
    {
        $this->assertNull(
            $this->request->feed()
        );

        $this->request->setFeed('/foo/bar');

        $this->assertSame(
            '/foo/bar',
            $this->request->feed()
        );

        $this->request->setFeed(null);

        $this->assertNull(
            $this->request->feed()
        );
    }

    public function testLimit()
    {
        $this->assertSame(
            5000,
            $this->request->limit()
        );

        $this->request->setLimit(1000);

        $this->assertSame(
            1000,
            $this->request->limit()
        );
    }

    public function testLimitWithInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Limit must be a positive integer.'
        );

        $this->request->setLimit(-1);
    }

    public function testOrder()
    {
        $this->assertSame(
            SORT_ASC,
            $this->request->order()
        );

        $this->request->setOrder(SORT_DESC);

        $this->assertSame(
            SORT_DESC,
            $this->request->order()
        );
    }

    public function testOrderWithInvalidValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Sort order must be SORT_ASC or SORT_DESC.'
        );

        $this->request->setOrder('<invalid>');
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->request->accept($visitor->mock());

        $visitor->visitAtomRequest->calledWith($this->request);
    }

    public function testSerialize()
    {
        $buffer  = serialize($this->request);
        $request = unserialize($buffer);

        $this->assertEquals(
            $this->request,
            $request
        );
    }
}
