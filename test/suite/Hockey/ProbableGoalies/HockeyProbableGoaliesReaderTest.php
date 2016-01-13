<?php

namespace Icecave\Siphon\Hockey\ProbableGoalies;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class HockeyProbableGoaliesReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new HockeyProbableGoaliesRequest(Sport::NHL());
        $this->response = new HockeyProbableGoaliesResponse(Sport::NHL());

        $this->modifiedTime = DateTime::fromUnixTime(43200);
        $this->response->setModifiedTime($this->modifiedTime);

        $season = new Season(
            '/sport/hockey/season:69',
            '2015-2016',
            Date::fromIsoString('2015-09-01'),
            Date::fromIsoString('2016-06-30')
        );

        $homeTeam = new TeamRef('/sport/hockey/team:4', 'NY Islanders');
        $homePlayer = new Player('/sport/hockey/player:2049', 'Thomas', 'Greiss');
        $awayTeam = new TeamRef('/sport/hockey/team:30', 'Columbus');
        $awayPlayer = new Player('/sport/hockey/player:4556', 'Anton', 'Forsberg');
        $competition = new Competition(
            '/sport/hockey/competition:70147',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2016-01-12T19:00:00-05:00'),
            null,
            Sport::NHL(),
            $season,
            $homeTeam,
            $awayTeam
        );

        $this->response->add($competition, $homeTeam, $homePlayer);
        $this->response->add($competition, $awayTeam, $awayPlayer);

        $homeTeam = new TeamRef('/sport/hockey/team:5', 'Carolina');
        $homePlayer = new Player('/sport/hockey/player:1224', 'Cam', 'Ward');
        $awayTeam = new TeamRef('/sport/hockey/team:7', 'Pittsburgh');
        $awayPlayer = new Player('/sport/hockey/player:164', 'Marc-Andre', 'Fleury');
        $competition = new Competition(
            '/sport/hockey/competition:70148',
            CompetitionStatus::SCHEDULED(),
            DateTime::fromIsoString('2016-01-12T19:00:00-05:00'),
            null,
            Sport::NHL(),
            $season,
            $homeTeam,
            $awayTeam
        );

        $this->response->add($competition, $homeTeam, $homePlayer);
        $this->response->add($competition, $awayTeam, $awayPlayer);

        $this->reader = new HockeyProbableGoaliesReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Hockey/probable-goalies.xml', $this->modifiedTime);
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/hockey/NHL/probable-goalies/probable_goalies_NHL.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(HockeyProbableGoaliesResponse::class))->argument();

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
