<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

/**
 * @covers Icecave\Siphon\Score\BoxScore\BoxScoreReader
 * @covers Icecave\Siphon\Player\StatisticsFactory
 */
class BoxScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->markTestSkipped();

        $this->reader = new BoxScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadWithPeriods()
    {
        $this->setUpXmlReader('Score/BoxScore/boxscores-period.xml');

        $result = $this->reader->read(
            'football',
            'NFL',
            '2009-2010',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml');

        $expected = new Result;

        // $scope1 = new Period(PeriodType::PERIOD(), 3, 7);
        // $scope2 = new Period(PeriodType::PERIOD(), 3, 0);

        // $expected = new PeriodResult;
        // $expected->setCurrentScope($scope2);
        // $expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
        // $expected->setCurrentGameTime(Duration::fromComponents(0, 0, 0, 14, 51));
        // $expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());

        // $score = new PeriodScore;
        // $score->add($scope1);
        // $score->add($scope2);

        // $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    // public function testReadWithPeriodsOnCompleteEvent()
    // {
    //     $this->setUpXmlReader('Score/LiveScore/livescores-period-complete.xml');

    //     $result = $this->reader->read(
    //         'football',
    //         'NFL',
    //         '/path/to/sport:12345'
    //     );

    //     $this
    //         ->xmlReader()
    //         ->read
    //         ->calledWith('/sport/v2/football/NFL/livescores/livescores_12345.xml');

    //     $expected = new PeriodResult;
    //     $expected->setCompetitionStatus(CompetitionStatus::COMPLETE());

    //     $score = new PeriodScore;
    //     $score->add(new Period(PeriodType::PERIOD(), 3,  7));
    //     $score->add(new Period(PeriodType::PERIOD(), 3,  0));
    //     $score->add(new Period(PeriodType::PERIOD(), 10, 3));
    //     $score->add(new Period(PeriodType::PERIOD(), 3,  5));

    //     $expected->setCompetitionScore($score);

    //     $this->assertEquals(
    //         $expected,
    //         $result
    //     );
    // }

    // public function testReadWithSpecialPeriods()
    // {
    //     $this->setUpXmlReader('Score/LiveScore/livescores-period-special.xml');

    //     $result = $this->reader->read(
    //         'hockey',
    //         'NHL',
    //         '/path/to/sport:12345'
    //     );

    //     $this
    //         ->xmlReader()
    //         ->read
    //         ->calledWith('/sport/v2/hockey/NHL/livescores/livescores_12345.xml');

    //     $scope1 = new Period(PeriodType::PERIOD(),   2, 0);
    //     $scope2 = new Period(PeriodType::PERIOD(),   0, 0);
    //     $scope3 = new Period(PeriodType::PERIOD(),   1, 3);
    //     $scope4 = new Period(PeriodType::OVERTIME(), 0, 0);
    //     $scope5 = new Period(PeriodType::SHOOTOUT(), 0, 0);
    //     $scope6 = new Period(PeriodType::SHOOTOUT(), 0, 1);
    //     $scope7 = new Period(PeriodType::SHOOTOUT(), 0, 0);

    //     $expected = new PeriodResult;
    //     $expected->setCurrentScope($scope7);
    //     $expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
    //     $expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());

    //     $score = new PeriodScore;
    //     $score->add($scope1);
    //     $score->add($scope2);
    //     $score->add($scope3);
    //     $score->add($scope4);
    //     $score->add($scope5);
    //     $score->add($scope6);
    //     $score->add($scope7);

    //     $expected->setCompetitionScore($score);

    //     $this->assertEquals(
    //         $expected,
    //         $result
    //     );
    // }

    // public function testReadWithInnings()
    // {
    //     $this->setUpXmlReader('Score/LiveScore/livescores-inning.xml');

    //     $result = $this->reader->read(
    //         'baseball',
    //         'MLB',
    //         '/path/to/sport:12345'
    //     );

    //     $this
    //         ->xmlReader()
    //         ->read
    //         ->calledWith('/sport/v2/baseball/MLB/livescores/livescores_12345.xml');

    //     $scope1 = new Inning(0, 0, 1, 0, 0, 0);
    //     $scope2 = new Inning(0, 1, 2, 2, 0, 0);

    //     $expected = new InningResult;
    //     $expected->setCurrentScope($scope2);
    //     $expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
    //     $expected->setCurrentInningSubType(InningSubType::BOTTOM());
    //     $expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());

    //     $score = new InningScore;
    //     $score->add($scope1);
    //     $score->add($scope2);

    //     $expected->setCompetitionScore($score);

    //     $this->assertEquals(
    //         $expected,
    //         $result
    //     );
    // }

    // public function testReadWithInningsOnCompleteEvent()
    // {
    //     $this->setUpXmlReader('Score/LiveScore/livescores-inning-complete.xml');

    //     $result = $this->reader->read(
    //         'baseball',
    //         'MLB',
    //         '/path/to/sport:12345'
    //     );

    //     $this
    //         ->xmlReader()
    //         ->read
    //         ->calledWith('/sport/v2/baseball/MLB/livescores/livescores_12345.xml');

    //     $expected = new InningResult;
    //     $expected->setCompetitionStatus(CompetitionStatus::COMPLETE());

    //     $score = new InningScore;
    //     $score->add(new Inning(0, 0, 1, 0, 0, 0));
    //     $score->add(new Inning(0, 1, 2, 2, 0, 0));
    //     $score->add(new Inning(0, 0, 0, 3, 0, 0));
    //     $score->add(new Inning(1, 2, 3, 2, 1, 0));
    //     $score->add(new Inning(0, 0, 1, 0, 0, 0));
    //     $score->add(new Inning(0, 0, 0, 2, 0, 0));
    //     $score->add(new Inning(0, 1, 2, 1, 0, 0));
    //     $score->add(new Inning(0, 3, 0, 5, 0, 1));
    //     $score->add(new Inning(0, 0, 1, 0, 0, 0));

    //     $expected->setCompetitionScore($score);

    //     $this->assertEquals(
    //         $expected,
    //         $result
    //     );
    // }
    // public function testReadWithUnsupportedCompetition()
    // {
    //     $this->setExpectedException(
    //         'InvalidArgumentException',
    //         'The provided competition could not be handled by any of the known live score factories.'
    //     );

    //     $this->reader->read(
    //         'unknown-sport',
    //         'unknown-league',
    //         '/path/to/sport:12345'
    //     );
    // }
}
