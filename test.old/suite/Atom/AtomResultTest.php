<?php
namespace Icecave\Siphon\Atom;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class AtomResultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);
        $this->entry1      = Phony::mock(AtomEntryInterface::class)->mock();
        $this->entry2      = Phony::mock(AtomEntryInterface::class)->mock();

        $this->result = new AtomResult(
            $this->updatedTime
        );
    }

    public function testAdd()
    {
        $this->result->add($this->entry1);
        $this->result->add($this->entry2);

        $this->assertSame(
            [
                $this->entry1,
                $this->entry2,
            ],
            $this->result->entries()
        );
    }

    public function testRemove()
    {
        $this->result->add($this->entry1);
        $this->result->add($this->entry2);

        $this->result->remove($this->entry1);

        $this->assertSame(
            [
                $this->entry2,
            ],
            $this->result->entries()
        );
    }

    public function testRemoveWithUnknownEntry()
    {
        $this->result->remove($this->entry1);

        $this->assertTrue(
            $this->result->isEmpty()
        );
    }

    public function testEntries()
    {
        $this->assertSame(
            [
            ],
            $this->result->entries()
        );

        $this->result->add($this->entry1);

        $this->assertSame(
            [
                $this->entry1,
            ],
            $this->result->entries()
        );

        $this->result->add($this->entry2);

        $this->assertSame(
            [
                $this->entry1,
                $this->entry2,
            ],
            $this->result->entries()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->result->isEmpty()
        );

        $this->result->add($this->entry1);
        $this->result->add($this->entry2);

        $this->assertFalse(
            $this->result->isEmpty()
        );

        $this->result->remove($this->entry1);

        $this->assertFalse(
            $this->result->isEmpty()
        );

        $this->result->remove($this->entry2);

        $this->assertTrue(
            $this->result->isEmpty()
        );
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->result->updatedTime()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->result)
        );

        $this->result->add($this->entry1);
        $this->result->add($this->entry2);

        $this->assertSame(
            2,
            count($this->result)
        );

        $this->result->remove($this->entry1);

        $this->assertSame(
            1,
            count($this->result)
        );

        $this->result->remove($this->entry2);

        $this->assertSame(
            0,
            count($this->result)
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
}
