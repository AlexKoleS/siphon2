<?php

namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class ScheduleReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new ScheduleRequest(
            Sport::MLB()
        );

        $this->response = new ScheduleResponse(
            Sport::MLB(),
            ScheduleType::FULL()
        );

        $season = new Season(
            '/sport/baseball/season:851',
            '2010',
            Date::fromIsoString('2010-03-02'),
            Date::fromIsoString('2010-11-15')
        );

        $comp1 = new Competition(
            '/sport/baseball/competition:294647',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2010-04-27T20:40:00-04:00'),
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2956', 'Colorado'),
            new TeamRef('/sport/baseball/team:2968', 'Arizona')
        );

        $comp1->addNotablePlayer(
            new Player(
                '/sport/baseball/player:42675',
                'Ubaldo',
                'Jimenez'
            )
        );

        $comp1->addNotablePlayer(
            new Player(
                '/sport/baseball/player:41499',
                'Edwin',
                'Jackson'
            )
        );

        $comp2 = new Competition(
            '/sport/baseball/competition:293835',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2010-04-27T22:05:00-04:00'),
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2979', 'LA Angels'),
            new TeamRef('/sport/baseball/team:2980', 'Cleveland')
        );

        $comp2->addNotablePlayer(
            new Player(
                '/sport/baseball/player:42548',
                'Joe',
                'Saunders'
            )
        );

        $comp2->addNotablePlayer(
            new Player(
                '/sport/baseball/player:43367',
                'Mitch',
                'Talbot'
            )
        );

        $comp3 = new Competition(
            '/sport/baseball/competition:295678',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2010-04-27T22:15:00-04:00'),
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2962', 'San Francisco'),
            new TeamRef('/sport/baseball/team:2958', 'Philadelphia')
        );

        $comp3->addNotablePlayer(
            new Player(
                '/sport/baseball/player:41429',
                'Todd',
                'Wellemeyer'
            )
        );

        $season->add($comp1);
        $season->add($comp2);
        $season->add($comp3);

        $this->response->add($season);

        $this->reader = new ScheduleReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadFullSchedule()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
    }

    public function testReadLimitedSchedule()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $this->request->setType(ScheduleType::LIMIT_2_DAYS());
        $this->response->setType(ScheduleType::LIMIT_2_DAYS());

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
    }

    public function testReadDeletedSchedule()
    {
        $this->setUpXmlReader('Schedule/schedule.xml');

        $this->request->setType(ScheduleType::DELETED());
        $this->response->setType(ScheduleType::DELETED());

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/games-deleted/games_deleted_MLB.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
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

    public function testReadNotFound()
    {
        $this->setUpXmlReaderNotFound();

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB.xml');

        $this->response->clear();

        $this->assertEquals(
            $this->response,
            $response
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
