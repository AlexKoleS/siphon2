<?php
namespace Icecave\Sid\Atom;

use Icecave\Chrono\DateTime;
use PHPUnit_Framework_TestCase;

class AtomEntryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->updatedTime = DateTime::fromUnixTime(0);

        $this->entry = new AtomEntry(
            '<url>',
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

    public function testUpdatedTime()
    {
        $this->assertSame(
            $this->updatedTime,
            $this->entry->updatedTime()
        );
    }
}
