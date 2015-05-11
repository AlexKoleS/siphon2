<?php
namespace Icecave\Siphon\Player;

use PHPUnit_Framework_TestCase;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->image = new Image(
            '<player-id>',
            '<url>',
            '<thumbnail>'
        );
    }

    public function testPlayerId()
    {
        $this->assertSame(
            '<player-id>',
            $this->image->playerId()
        );
    }

    public function testUrl()
    {
        $this->assertSame(
            '<url>',
            $this->image->url()
        );
    }

    public function testThumbnailUrl()
    {
        $this->assertSame(
            '<thumbnail>',
            $this->image->thumbnailUrl()
        );
    }
}
