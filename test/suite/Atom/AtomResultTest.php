<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class AtomResultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);

        $this->entry1 = new AtomEntry(
            '<url-1>',
            DateTime::fromUnixTime(1)
        );

        $this->entry2 = new AtomEntry(
            '<url-2>',
            DateTime::fromUnixTime(2)
        );

        $this->result = new AtomResult(
            $this->updatedTime
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->result->isEmpty()
        );

        $this->result->add($this->entry1);

        $this->assertFalse(
            $this->result->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            $this->result->count()
        );

        $this->result->add($this->entry1);

        $this->assertSame(
            1,
            $this->result->count()
        );

        $this->result->add($this->entry2);

        $this->assertSame(
            2,
            $this->result->count()
        );
    }

    public function testGetIterator()
    {
        $this->result->add($this->entry1);
        $this->result->add($this->entry2);

        $this->assertEquals(
            [
                $this->entry1,
                $this->entry2,
            ],
            iterator_to_array($this->result)
        );
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->result->updatedTime()
        );
    }
}
