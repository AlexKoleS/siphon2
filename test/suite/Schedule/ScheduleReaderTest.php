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
use React\Promise;
use RuntimeException;

class ScheduleReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->modifiedTime = DateTime::fromUnixTime(43200);

        $this->request = new ScheduleRequest(Sport::MLB());

        $this->response = new ScheduleResponse(Sport::MLB(), ScheduleType::FULL());

        $this->response->setModifiedTime($this->modifiedTime);

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
            null,
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2956', 'Colorado'),
            new TeamRef('/sport/baseball/team:2968', 'Arizona')
        );

        $comp1->addNotablePlayer(new Player('/sport/baseball/player:42675', 'Ubaldo', 'Jimenez'));
        $comp1->addNotablePlayer(new Player('/sport/baseball/player:41499', 'Edwin', 'Jackson'));

        $comp2 = new Competition(
            '/sport/baseball/competition:293835',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2010-04-27T22:05:00-04:00'),
            null,
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2979', 'LA Angels'),
            new TeamRef('/sport/baseball/team:2980', 'Cleveland')
        );

        $comp2->addNotablePlayer(new Player('/sport/baseball/player:42548', 'Joe', 'Saunders'));
        $comp2->addNotablePlayer(new Player('/sport/baseball/player:43367', 'Mitch', 'Talbot'));

        $comp3 = new Competition(
            '/sport/baseball/competition:295678',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2010-04-27T22:15:00-04:00'),
            null,
            Sport::MLB(),
            $season,
            new TeamRef('/sport/baseball/team:2962', 'San Francisco'),
            new TeamRef('/sport/baseball/team:2958', 'Philadelphia')
        );

        $comp3->addNotablePlayer(new Player('/sport/baseball/player:41429', 'Todd', 'Wellemeyer'));

        $season->add($comp1);
        $season->add($comp2);
        $season->add($comp3);

        $this->response->add($season);

        $this->reader = new ScheduleReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testReadFullSchedule()
    {
        $this->setUpXmlReader('Schedule/schedule.xml', $this->modifiedTime);
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/baseball/MLB/schedule/schedule_MLB.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(ScheduleResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadExceptionPropagation()
    {
        $exception = new RuntimeException('You done goofed.');
        $this->xmlReader()->read->returns(Promise\reject($exception));
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->reject->calledWith($exception);
        $this->resolve->never()->called();
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
