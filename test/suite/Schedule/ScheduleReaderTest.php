<?php
namespace Icecave\Siphon\Schedule;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\XmlReaderInterface;
use PHPUnit_Framework_TestCase;

class ScheduleReaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->xmlReader = Phony::mock(XmlReaderInterface::class);

        $this
            ->xmlReader
            ->read
            ->returns(
                simplexml_load_string(
                    file_get_contents(__DIR__ . '/../../fixture/Schedule/schedule.xml')
                )
            );

        $this->reader = new ScheduleReader(
            $this->xmlReader->mock()
        );
    }

    public function testRead()
    {
        $schedule = $this->reader->read('baseball', 'MLB');

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/schedule/schedule_MLB.xml');

        $expected = new Schedule;

        $season = new Season(
            '/sport/baseball/season:851',
            '2010',
            Date::fromIsoString('2010-03-02'),
            Date::fromIsoString('2010-11-15')
        );

        $expected->add($season);

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

        $this->assertEquals(
            $expected,
            $schedule
        );
    }
}
