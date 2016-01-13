<?php

namespace Icecave\Siphon\Result;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class ResultReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->modifiedTime = DateTime::fromUnixTime(43200);

        $this->request = new ResultRequest(Sport::NHL(), '2015-2016');

        $this->season = new Season(
            '/sport/hockey/season:69',
            '2015-2016',
            Date::fromIsoString('2015-09-01'),
            Date::fromIsoString('2016-06-30')
        );

        $this->response = new ResultResponse(
            Sport::NHL(),
            $this->season
        );

        $this->response->setModifiedTime($this->modifiedTime);

        $this->season->add(
            new Competition(
                '/sport/hockey/competition:72767',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T16:30:00-04:00'),
                DateTime::fromIsoString('2015-09-20T19:06:31-04:00'),
                Sport::NHL(),
                $this->season,
                new TeamRef('/sport/hockey/team:27', 'Nashville'),
                new TeamRef('/sport/hockey/team:14', 'Florida')
            )
        );

        $this->season->add(
            new Competition(
                '/sport/hockey/competition:72768',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T19:00:00-04:00'),
                DateTime::fromIsoString('2015-09-20T22:02:30-04:00'),
                Sport::NHL(),
                $this->season,
                new TeamRef('/sport/hockey/team:3', 'Boston'),
                new TeamRef('/sport/hockey/team:13', 'New Jersey')
            )
        );

        $this->season->add(
            new Competition(
                '/sport/hockey/competition:72769',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T20:00:00-04:00'),
                DateTime::fromIsoString('2015-09-20T22:37:40-04:00'),
                Sport::NHL(),
                $this->season,
                new TeamRef('/sport/hockey/team:27', 'Nashville'),
                new TeamRef('/sport/hockey/team:14', 'Florida')
            )
        );

        $this->subject = new ResultReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Result/results.xml', $this->modifiedTime);
        $this->subject->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/hockey/NHL/results/2015-2016/results_NHL.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(ResultResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadEmpty()
    {
        $this->setUpXmlReader('Result/empty.xml', $this->modifiedTime);

        $this->setExpectedException(NotFoundException::class);
        $this->subject->read($this->request)->done();
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->subject->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    public function testIsSupported()
    {
        $this->assertTrue($this->subject->isSupported($this->request));
        $this->assertFalse($this->subject->isSupported(Phony::mock(RequestInterface::class)->mock()));
    }
}
