<?php

namespace Icecave\Siphon\Player\Injury;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class InjuryReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request  = new InjuryRequest(Sport::NFL());
        $this->response = new InjuryResponse(Sport::NFL());

        $this->response->add(
            new Player('/sport/football/player:1633', 'Rod', 'Coleman'),
            new Injury(
                '/sport/football/injury:13715',
                'Quadricep',
                InjuryStatus::OUT(),
                'Early Sept',
                'Underwent surgery April 30th to repair a ruptured right quadriceps that could keep him sidelined into September.',
                Date::fromIsoString('2007-05-24')
            )
        );

        $this->response->add(
            new Player('/sport/football/player:11743', 'Tank', 'Johnson'),
            new Injury(
                '/sport/football/injury:13717',
                'Suspension',
                InjuryStatus::OUT(),
                'Elig Nov 11',
                'Suspended for the first eight games of the season for violating the new personal conduct policy. He can trim two games off his suspension if he has no further legal incidents.',
                Date::fromIsoString('2007-06-04'),
                DateTime::fromIsoString('2007-06-04T10:58:42-03:00')
            )
        );

        $this->reader = new InjuryReader($this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/injuries.xml');
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith('/sport/v2/football/NFL/injuries/injuries_NFL.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(InjuryResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->reader->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    public function testIsSupported()
    {
        $this->assertTrue($this->reader->isSupported($this->request));
        $this->assertFalse($this->reader->isSupported(Phony::mock(RequestInterface::class)->mock()));
    }
}
