<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
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

        $season->add(
            new Competition(
                '/sport/baseball/competition:294647',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T20:40:00-04:00'),
                Sport::MLB(),
                $season,
                new TeamRef('/sport/baseball/team:2956', 'Colorado'),
                new TeamRef('/sport/baseball/team:2968', 'Arizona')
            )
        );

        $season->add(
            new Competition(
                '/sport/baseball/competition:293835',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T22:05:00-04:00'),
                Sport::MLB(),
                $season,
                new TeamRef('/sport/baseball/team:2979', 'LA Angels'),
                new TeamRef('/sport/baseball/team:2980', 'Cleveland')
            )
        );

        $season->add(
            new Competition(
                '/sport/baseball/competition:295678',
                CompetitionStatus::SCHEDULED(),
                DateTime::fromIsoString('2010-04-27T22:15:00-04:00'),
                Sport::MLB(),
                $season,
                new TeamRef('/sport/baseball/team:2962', 'San Francisco'),
                new TeamRef('/sport/baseball/team:2958', 'Philadelphia')
            )
        );

        $this->response->add($season);

        // $this->expected = new Schedule;
        // $this->expected->add($season);

        // $season = new Season(
        //     '/sport/hockey/season:19',
        //     '2009-2010',
        //     Date::fromIsoString('2009-10-01'),
        //     Date::fromIsoString('2010-06-30')
        // );

        // $season->add(
        //     new Competition(
        //         '/sport/hockey/competition:32577',
        //         CompetitionStatus::SCHEDULED(),
        //         DateTime::fromIsoString('2010-04-16T22:00:00-04:00'),
        //         'hockey',
        //         'NHL',
        //         '/sport/hockey/team:19',
        //         '/sport/hockey/team:20'
        //     )
        // );

        // $season->add(
        //     new Competition(
        //         '/sport/hockey/competition:32539',
        //         CompetitionStatus::SCHEDULED(),
        //         DateTime::fromIsoString('2010-04-25T19:00:00-04:00'),
        //         'hockey',
        //         'NHL',
        //         '/sport/hockey/team:12',
        //         '/sport/hockey/team:13'
        //     )
        // );

        // $this->expectedDeleted = new Schedule;
        // $this->expectedDeleted->add($season);

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
