<?php

namespace Icecave\Siphon\Result;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
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
        $this->request = new ResultRequest(Sport::NHL(), '2015-2016');

        $this->response = new ResultResponse(
            Sport::NHL(),
            new Season(
                '/sport/hockey/season:69',
                '2015-2016',
                Date::fromIsoString('2015-09-01'),
                Date::fromIsoString('2016-06-30')
            )
        );

        $this->response->add(
            new Competition(
                '/sport/hockey/competition:72767',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T16:30:00-04:00'),
                Sport::NHL(),
                $this->response->season(),
                new TeamRef('/sport/hockey/team:27', 'Nashville'),
                new TeamRef('/sport/hockey/team:14', 'Florida')
            ),
            true
        );
        $this->response->add(
            new Competition(
                '/sport/hockey/competition:72768',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T19:00:00-04:00'),
                Sport::NHL(),
                $this->response->season(),
                new TeamRef('/sport/hockey/team:3', 'Boston'),
                new TeamRef('/sport/hockey/team:13', 'New Jersey')
            ),
            true
        );
        $this->response->add(
            new Competition(
                '/sport/hockey/competition:72769',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2015-09-20T20:00:00-04:00'),
                Sport::NHL(),
                $this->response->season(),
                new TeamRef('/sport/hockey/team:27', 'Nashville'),
                new TeamRef('/sport/hockey/team:14', 'Florida')
            ),
            true
        );

        $this->reader = new ResultReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Result/results.xml');
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/hockey/NHL/results/2015-2016/results_NHL.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(ResultResponse::class))->argument();

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
