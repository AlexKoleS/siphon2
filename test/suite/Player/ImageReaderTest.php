<?php
namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
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

        $this->response->add(new Player('/sport/baseball/player:43559',  'Yonder',    'Alonso'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466910.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466910.jpg');
        $this->response->add(new Player('/sport/baseball/player:104275', 'Alexi',     'Amarista'),     'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466883.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466883.jpg');
        $this->response->add(new Player('/sport/baseball/player:42165',  'Clint',     'Barmes'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466864.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466864.jpg');
        $this->response->add(new Player('/sport/baseball/player:41227',  'Joaquin',   'Benoit'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466862.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466862.jpg');
        $this->response->add(new Player('/sport/baseball/player:105775', 'Andrew',    'Cashner'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466836.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466836.jpg');
        $this->response->add(new Player('/sport/baseball/player:116554', 'Odrisamer', 'Despaigne'),    'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466913.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466913.jpg');
        $this->response->add(new Player('/sport/baseball/player:104398', 'Tim',       'Federowicz'),   'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466865.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466865.jpg');
        $this->response->add(new Player('/sport/baseball/player:117235', 'Frank',     'Garces'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466867.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466867.jpg');
        $this->response->add(new Player('/sport/baseball/player:109207', 'Jedd',      'Gyorko'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466918.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466918.jpg');
        $this->response->add(new Player('/sport/baseball/player:42600',  'Josh',      'Johnson'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466840.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466840.jpg');
        $this->response->add(new Player('/sport/baseball/player:43779',  'Shawn',     'Kelley'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466832.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466832.jpg');
        $this->response->add(new Player('/sport/baseball/player:42874',  'Matt',      'Kemp'),         'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466908.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466908.jpg');
        $this->response->add(new Player('/sport/baseball/player:43241',  'Ian',       'Kennedy'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466831.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466831.jpg');
        $this->response->add(new Player('/sport/baseball/player:43916',  'Craig',     'Kimbrel'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8433731.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8433731.jpg');
        $this->response->add(new Player('/sport/baseball/player:106268', 'Cory',      'Luebke'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466879.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466879.jpg');
        $this->response->add(new Player('/sport/baseball/player:104395', 'Will',      'Middlebrooks'), 'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466900.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466900.jpg');
        $this->response->add(new Player('/sport/baseball/player:43118',  'Brandon',   'Morrow'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466829.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466829.jpg');
        $this->response->add(new Player('/sport/baseball/player:44043',  'Wil',       'Myers'),        'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466916.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466916.jpg');
        $this->response->add(new Player('/sport/baseball/player:41582',  'Wil',       'Nieves'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466834.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466834.jpg');
        $this->response->add(new Player('/sport/baseball/player:104375', 'Derek',     'Norris'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466902.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466902.jpg');
        $this->response->add(new Player('/sport/baseball/player:43926',  'Tyson',     'Ross'),         'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466896.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466896.jpg');
        $this->response->add(new Player('/sport/baseball/player:42814',  'James',     'Shields'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/7788700.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/7788700.jpg');
        $this->response->add(new Player('/sport/baseball/player:104915', 'Yangervis', 'Solarte'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466869.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466869.jpg');
        $this->response->add(new Player('/sport/baseball/player:113194', 'Cory',      'Spangenberg'),  'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466898.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466898.jpg');
        $this->response->add(new Player('/sport/baseball/player:43749',  'Dale',      'Thayer'),       'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466884.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466884.jpg');
        $this->response->add(new Player('/sport/baseball/player:43211',  'Justin',    'Upton'),        'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466845.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466845.jpg');
        $this->response->add(new Player('/sport/baseball/player:42148',  'Melvin',    'Upton Jr.'),    'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8433756.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8433756.jpg');
        $this->response->add(new Player('/sport/baseball/player:43564',  'Will',      'Venable'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466833.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466833.jpg');
        $this->response->add(new Player('/sport/baseball/player:106675', 'Nick',      'Vincent'),      'http://thumb.usatodaysportsimages.com/image/thumb/650-650nw/8466841.jpg', 'http://thumb.usatodaysportsimages.com/image/thumb/250-250/8466841.jpg');

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
