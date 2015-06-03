<?php
namespace Icecave\Siphon\Player;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class InjuryReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->expected = [
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
        ];

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
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntry()
    {
        $this->setUpXmlReader('Player/player-injuries.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/football/NFL/injuries/injuries_NFL.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/injuries/injuries_NFL.xml');

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
                    '/sport/v2/football/NFL/injuries/injuries_NFL.xml',
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
            '/sport/v2/football/NFL/injuries/injuries_NFL.xml',
            [],
            DateTime::fromUnixTime(0)
        );

        $parameters = [];

        $this->assertTrue(
            $this->reader->supportsAtomEntry($entry, $parameters)
        );

        $this->assertSame(
            [
                'sport'  => 'football',
                'league' => 'NFL',
            ],
            $parameters
        );
    }
}
