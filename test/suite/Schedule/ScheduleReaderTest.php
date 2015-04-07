<?php
namespace Icecave\Siphon\Schedule;

use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class ScheduleReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $season = new Season(
            '/sport/baseball/season:851',
            '2010',
            Date::fromIsoString('2010-03-02'),
            Date::fromIsoString('2010-11-15')
        );

        $season->add(
            new Competition(
                '/sport/baseball/competition:294647',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T20:40:00-04:00'),
                'baseball',
                'MLB',
                '/sport/baseball/team:2956',
                '/sport/baseball/team:2968'
            )
        );

        $season->add(
            new Competition(
                '/sport/baseball/competition:293835',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T22:05:00-04:00'),
                'baseball',
                'MLB',
                '/sport/baseball/team:2979',
                '/sport/baseball/team:2980'
            )
        );

        $season->add(
            new Competition(
                '/sport/baseball/competition:295678',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T22:15:00-04:00'),
                'baseball',
                'MLB',
                '/sport/baseball/team:2962',
                '/sport/baseball/team:2958'
            )
        );

        $this->expected = new Schedule;
        $this->expected->add($season);

        $season = new Season(
            '/sport/hockey/season:19',
            '2009-2010',
            Date::fromIsoString('2009-10-01'),
            Date::fromIsoString('2010-06-30')
        );

        $season->add(
            new Competition(
                '/sport/hockey/competition:32577',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-16T22:00:00-04:00'),
                'hockey',
                'NHL',
                '/sport/hockey/team:19',
                '/sport/hockey/team:20'
            )
        );

        $season->add(
            new Competition(
                '/sport/hockey/competition:32539',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-25T19:00:00-04:00'),
                'hockey',
                'NHL',
                '/sport/hockey/team:12',
                '/sport/hockey/team:13'
            )
        );

        $this->expectedDeleted = new Schedule;
        $this->expectedDeleted->add($season);

        $this->reader = new ScheduleReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $schedule = $this->reader->read('baseball', 'MLB');

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB.xml');

        $this->assertEquals(
            $this->expected,
            $schedule
        );
    }

    public function testReadWithLimit()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $schedule = $this->reader->read('baseball', 'MLB', ScheduleLimit::DAYS_2());

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml');

        $this->assertEquals(
            $this->expected,
            $schedule
        );
    }

    public function testReadDeleted()
    {
        $this->setUpXmlReader('Schedule/deleted.xml');

        $schedule = $this->reader->readDeleted('hockey', 'NHL');

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/hockey/NHL/games-deleted/games_deleted_NHL.xml');

        $this->assertEquals(
            $this->expectedDeleted,
            $schedule
        );
    }

    public function testReadAtomEntry()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/baseball/MLB/schedule/schedule_MLB.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntryWithLimit()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntryDeleted()
    {
        $this->setUpXmlReader('Schedule/deleted.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/hockey/NHL/games-deleted/games_deleted_NHL.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/hockey/NHL/games-deleted/games_deleted_NHL.xml');

        $this->assertEquals(
            $this->expectedDeleted,
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
                    '/sport/v2/baseball/MLB/schedule/schedule_MLB.xml',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertTrue(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<url>',
                    '/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertTrue(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<url>',
                    '/sport/v2/hockey/NHL/games-deleted/games_deleted_NHL.xml',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );
    }
}
