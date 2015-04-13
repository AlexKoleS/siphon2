<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class ImageReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->expected = [
            new Image(
                '/sport/baseball/player:43559',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466910.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466910.jpg'
            ),
            new Image(
                '/sport/baseball/player:104275',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466883.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466883.jpg'
            ),
            new Image(
                '/sport/baseball/player:42165',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466864.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466864.jpg'
            ),
            new Image(
                '/sport/baseball/player:41227',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466862.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466862.jpg'
            ),
            new Image(
                '/sport/baseball/player:105775',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466836.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466836.jpg'
            ),
            new Image(
                '/sport/baseball/player:116554',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466913.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466913.jpg'
            ),
            new Image(
                '/sport/baseball/player:104398',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466865.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466865.jpg'
            ),
            new Image(
                '/sport/baseball/player:117235',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466867.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466867.jpg'
            ),
            new Image(
                '/sport/baseball/player:109207',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466918.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466918.jpg'
            ),
            new Image(
                '/sport/baseball/player:42600',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466840.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466840.jpg'
            ),
            new Image(
                '/sport/baseball/player:43779',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466832.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466832.jpg'
            ),
            new Image(
                '/sport/baseball/player:42874',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466908.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466908.jpg'
            ),
            new Image(
                '/sport/baseball/player:43241',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466831.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466831.jpg'
            ),
            new Image(
                '/sport/baseball/player:43916',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8433731.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8433731.jpg'
            ),
            new Image(
                '/sport/baseball/player:106268',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466879.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466879.jpg'
            ),
            new Image(
                '/sport/baseball/player:104395',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466900.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466900.jpg'
            ),
            new Image(
                '/sport/baseball/player:43118',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466829.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466829.jpg'
            ),
            new Image(
                '/sport/baseball/player:44043',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466916.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466916.jpg'
            ),
            new Image(
                '/sport/baseball/player:41582',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466834.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466834.jpg'
            ),
            new Image(
                '/sport/baseball/player:104375',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466902.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466902.jpg'
            ),
            new Image(
                '/sport/baseball/player:43926',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466896.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466896.jpg'
            ),
            new Image(
                '/sport/baseball/player:42814',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/7788700.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/7788700.jpg'
            ),
            new Image(
                '/sport/baseball/player:104915',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466869.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466869.jpg'
            ),
            new Image(
                '/sport/baseball/player:113194',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466898.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466898.jpg'
            ),
            new Image(
                '/sport/baseball/player:43749',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466884.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466884.jpg'
            ),
            new Image(
                '/sport/baseball/player:43211',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466845.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466845.jpg'
            ),
            new Image(
                '/sport/baseball/player:42148',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8433756.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8433756.jpg'
            ),
            new Image(
                '/sport/baseball/player:43564',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466833.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466833.jpg'
            ),
            new Image(
                '/sport/baseball/player:106675',
                'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466841.jpg',
                'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466841.jpg'
            ),
        ];

        $this->reader = new ImageReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/player-images.xml');

        $result = $this->reader->read(
            'baseball',
            'MLB',
            '2015',
            '/sport/baseball/team:12345'
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntry()
    {
        $this->setUpXmlReader('Player/player-images.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntryFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported atom entry.'
        );

        $this->reader->readAtomEntry(
            new AtomEntry(
                '<atom-url>',
                '<atom-resource>',
                ['foo' => 'bar'],
                DateTime::fromUnixTime(0)
            )
        );
    }

    public function testSupportsAtomEntry()
    {
        $this->assertFalse(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<atom-url>',
                    '<atom-resource>',
                    ['foo' => 'bar'],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertFalse(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<atom-url>',
                    '<atom-resource>',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertTrue(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<url>',
                    '/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );
    }

    public function testSupportsAtomEntryParameters()
    {
        $entry = new AtomEntry(
            '<atom-url>',
            '/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml',
            [],
            DateTime::fromUnixTime(0)
        );

        $parameters = [];

        $this->assertTrue(
            $this->reader->supportsAtomEntry($entry, $parameters)
        );

        $this->assertSame(
            [
                'sport'  => 'baseball',
                'league' => 'MLB',
                'season' => '2015',
                'teamId' => '/sport/baseball/team:12345',
            ],
            $parameters
        );
    }
}
