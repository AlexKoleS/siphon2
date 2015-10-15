<?php

namespace Icecave\Siphon\Player\Image;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class ImageReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new ImageRequest(
            Sport::MLB(),
            '2015',
            2955
        );

        $this->response = new ImageResponse(
            Sport::MLB(),
            new Season(
                '/sport/baseball/season:927',
                '2015',
                Date::fromIsoString('2015-02-01'),
                Date::fromIsoString('2015-11-30')
            ),
            new TeamRef(
                '/sport/baseball/team:2955',
                'San Diego'
            )
        );

        $this->response->add(
            new Player('/sport/baseball/player:41227',  'Joaquin',   'Benoit'),
            'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466862.jpg',
            'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466862.jpg'
        );

        $this->response->add(
            new Player('/sport/baseball/player:43559',  'Yonder',    'Alonso'),
            'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466910.jpg',
            null
        );

        $this->response->add(
            new Player('/sport/baseball/player:104275', 'Alexi',     'Amarista'),
            null,
            'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466883.jpg'
        );

        $this->reader = new ImageReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/images.xml');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/player-images/2015/player-images_2955_MLB.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
    }

    public function testReadEmpty()
    {
        $this->setUpXmlReader('Player/empty.xml');

        $this->setExpectedException(NotFoundException::class);

        $this
            ->reader
            ->read($this->request);
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported request.'
        );

        $this->reader->read(
            Phony::mock(RequestInterface::class)->mock()
        );
    }

    public function testIsSupported()
    {
        $this->assertTrue(
            $this->reader->isSupported($this->request)
        );

        $this->assertFalse(
            $this->reader->isSupported(
                Phony::mock(RequestInterface::class)->mock()
            )
        );
    }
}
