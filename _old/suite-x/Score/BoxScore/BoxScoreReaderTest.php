<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Score\Innings;
use Icecave\Siphon\Score\InningsType;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Score\ScopeStatus;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

/**
 * @covers Icecave\Siphon\Score\BoxScore\BoxScoreReader
 * @covers Icecave\Siphon\Score\BoxScore\InningsBoxScoreFactory
 * @covers Icecave\Siphon\Score\BoxScore\PeriodBoxScoreFactory
 */
class BoxScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new BoxScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadWithPeriods()
    {
        $this->setUpXmlReader('Score/BoxScore/boxscores-period.xml');

        $boxScore = $this->reader->read(
            'football',
            'NFL',
            '2009-2010',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml');

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

        $expected = new PeriodScore;
        $expected->setGameTime(Duration::fromComponents(0, 0, 0, 14, 51));
        $expected->add($scope1);
        $expected->add($scope2);

        $this->assertEquals(
            $expected,
            $boxScore
        );
    }

    public function testReadWithSpecialPeriods()
    {
        $this->markTestSkipped();

        $this->setUpXmlReader('BoxScore/boxscores-period-special.xml');

        $boxScore = $this->reader->read(
            'hockey',
            'NHL',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/hockey/NHL/boxscores/boxscores_12345.xml');

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

        $expected = new PeriodScore;
        $expected->add($scope1);
        $expected->add($scope2);
        $expected->add($scope3);
        $expected->add($scope4);
        $expected->add($scope5);
        $expected->add($scope6);
        $expected->add($scope7);

        $this->assertEquals(
            $expected,
            $boxScore
        );
    }

    public function testReadWithInnings()
    {
        $this->markTestSkipped();

        $this->setUpXmlReader('BoxScore/boxscores-innings.xml');

        $boxScore = $this->reader->read(
            'baseball',
            'MLB',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/baseball/MLB/boxscores/boxscores_12345.xml');

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

        $expected = new InningsScore;
        $expected->setCurrentInningsType(InningsType::BOTTOM());
        $expected->add($scope1);
        $expected->add($scope2);

        $this->assertEquals(
            $expected,
            $boxScore
        );
    }

    public function testReadWithUnsupportedCompetition()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The provided competition could not be handled by any of the known boxscore factories.'
        );

        $boxScore = $this->reader->read(
            'unknown-sport',
            'unknown-league',
            '2009-2010',
            '/path/to/sport:12345'
        );
    }
}
