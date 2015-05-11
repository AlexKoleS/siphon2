<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class AtomEntryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);

        $this->entry = new AtomEntry(
            '<url>',
            '<resource>',
            ['foo' => 'bar'],
            $this->updatedTime
        );
    }

    public function testUrl()
    {
        $this->assertEquals(
            '<url>',
            $this->entry->url()
        );
    }

    public function testResource()
    {
        $this->assertEquals(
            '<resource>',
            $this->entry->resource()
        );
    }

    public function testParameters()
    {
        $this->assertEquals(
            ['foo' => 'bar'],
            $this->entry->parameters()
        );
    }

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->entry->updatedTime()
        );
    }
}
