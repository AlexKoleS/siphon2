<?php
namespace Icecave\Siphon\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\LiveScore\Innings\Innings;
use Icecave\Siphon\LiveScore\Innings\InningsLiveScore;
use Icecave\Siphon\LiveScore\Innings\InningsType;
use Icecave\Siphon\LiveScore\Period\Period;
use Icecave\Siphon\LiveScore\Period\PeriodLiveScore;
use Icecave\Siphon\LiveScore\Period\PeriodType;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

/**
 * @covers Icecave\Siphon\LiveScore\LiveScoreReader
 * @covers Icecave\Siphon\LiveScore\Innings\InningsLiveScoreFactory
 * @covers Icecave\Siphon\LiveScore\Period\PeriodLiveScoreFactory
 */
class LiveScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new LiveScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadWithPeriods()
    {
        $competition = new Competition(
            '/path/to/sport:12345',
            CompetitionStatus::IN_PROGRESS(),
            Phony::fullMock(DateTime::class)->mock(),
            'football',
            'NFL',
            '<home>',
            '<away>'
        );

        $this->setUpXmlReader('LiveScore/livescores-period.xml');

        $liveScore = $this->reader->read($competition);

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/football/NFL/livescores/livescores_12345.xml');

        $scope1 = new Period(
            3, // home team points
            7  // away team points
        );
        $scope1->setStatus(ScopeStatus::COMPLETE());

        $scope2 = new Period(
            3, // home team points
            0  // away team points
        );
        $scope2->setStatus(ScopeStatus::IN_PROGRESS());

        $expected = new PeriodLiveScore;
        $expected->setGameTime(Duration::fromComponents(0, 0, 0, 14, 51));
        $expected->add($scope1);
        $expected->add($scope2);

        $this->assertEquals(
            $expected,
            $liveScore
        );
    }

    public function testReadWithSpecialPeriods()
    {
        $competition = new Competition(
            '/path/to/sport:12345',
            CompetitionStatus::IN_PROGRESS(),
            Phony::fullMock(DateTime::class)->mock(),
            'hockey',
            'NHL',
            '<home>',
            '<away>'
        );

        $this->setUpXmlReader('LiveScore/livescores-period-special.xml');

        $liveScore = $this->reader->read($competition);

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/hockey/NHL/livescores/livescores_12345.xml');

        $scope1 = new Period(
            2, // home team points
            0  // away team points
        );
        $scope1->setStatus(ScopeStatus::COMPLETE());

        $scope2 = new Period(
            0, // home team points
            0  // away team points
        );
        $scope2->setStatus(ScopeStatus::COMPLETE());

        $scope3 = new Period(
            1, // home team points
            3  // away team points
        );
        $scope3->setStatus(ScopeStatus::COMPLETE());

        $scope4 = new Period(
            0, // home team points
            0  // away team points
        );
        $scope4->setStatus(ScopeStatus::COMPLETE());
        $scope4->setType(PeriodType::OVERTIME());

        $scope5 = new Period(
            0, // home team points
            0  // away team points
        );
        $scope5->setStatus(ScopeStatus::COMPLETE());
        $scope5->setType(PeriodType::SHOOTOUT());

        $scope6 = new Period(
            0, // home team points
            1  // away team points
        );
        $scope6->setStatus(ScopeStatus::COMPLETE());
        $scope6->setType(PeriodType::SHOOTOUT());

        $scope7 = new Period(
            0, // home team points
            0  // away team points
        );
        $scope7->setStatus(ScopeStatus::IN_PROGRESS());
        $scope7->setType(PeriodType::SHOOTOUT());

        $expected = new PeriodLiveScore;
        $expected->add($scope1);
        $expected->add($scope2);
        $expected->add($scope3);
        $expected->add($scope4);
        $expected->add($scope5);
        $expected->add($scope6);
        $expected->add($scope7);

        $this->assertEquals(
            $expected,
            $liveScore
        );
    }

    public function testReadWithInnings()
    {
        $competition = new Competition(
            '/path/to/sport:12345',
            CompetitionStatus::IN_PROGRESS(),
            Phony::fullMock(DateTime::class)->mock(),
            'baseball',
            'MLB',
            '<home>',
            '<away>'
        );

        $this->setUpXmlReader('LiveScore/livescores-innings.xml');

        $liveScore = $this->reader->read($competition);

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/baseball/MLB/livescores/livescores_12345.xml');

        $scope1 = new Innings(
            0, // home team runs
            0, // away team runs
            1, // home team hits
            0, // away team hits
            0, // home team errors
            0  // away team errors
        );
        $scope1->setStatus(ScopeStatus::COMPLETE());

        $scope2 = new Innings(
            0, // home team runs
            1, // away team runs
            2, // home team hits
            2, // away team hits
            0, // home team errors
            0  // away team errors
        );
        $scope2->setStatus(ScopeStatus::IN_PROGRESS());

        $expected = new InningsLiveScore;
        $expected->setCurrentInningsType(InningsType::BOTTOM());
        $expected->add($scope1);
        $expected->add($scope2);

        $this->assertEquals(
            $expected,
            $liveScore
        );
    }

    public function testReadWithUnsupportedCompetition()
    {
        $competition = new Competition(
            '/path/to/sport:12345',
            CompetitionStatus::IN_PROGRESS(),
            Phony::fullMock(DateTime::class)->mock(),
            '<sport>',
            '<league>',
            '<home>',
            '<away>'
        );

        $this->setExpectedException(
            'InvalidArgumentException',
            'The provided competition could not be handled by any of the known live score factories.'
        );

        $this->reader->read($competition);
    }
}
