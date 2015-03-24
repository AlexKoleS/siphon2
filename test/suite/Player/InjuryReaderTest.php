<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class InjuryReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new InjuryReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/player-injuries.xml');

        $result = $this->reader->read(
            'football',
            'NFL'
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/injuries/injuries_NFL.xml');

        $this->assertEquals(
            [
                new Injury(
                    '/sport/football/injury:13715',
                    '/sport/football/player:1633',
                    'Quadricep',
                    InjuryStatus::OUT(),
                    'Early Sept',
                    'Underwent surgery April 30th to repair a ruptured right quadriceps that could keep him sidelined into September.',
                    Date::fromIsoString('2007-05-24')
                ),
                new Injury(
                    '/sport/football/injury:13717',
                    '/sport/football/player:11743',
                    'Suspension',
                    InjuryStatus::OUT(),
                    'Elig Nov 11',
                    'Suspended for the first eight games of the season for violating the new personal conduct policy. He can trim two games off his suspension if he has no further legal incidents.',
                    Date::fromIsoString('2007-06-04'),
                    DateTime::fromIsoString('2007-06-04T10:58:42-03:00')
                ),
            ],
            $result
        );
    }
}
