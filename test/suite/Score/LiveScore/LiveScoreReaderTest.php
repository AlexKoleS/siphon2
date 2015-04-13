<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\DateTime;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class LiveScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $scope1 = new Period(PeriodType::PERIOD(), 3, 7);
        $scope2 = new Period(PeriodType::PERIOD(), 3, 0);

        $score = new PeriodScore;
        $score->add($scope1);
        $score->add($scope2);

        $this->expected = new PeriodResult;
        $this->expected->setCurrentScope($scope2);
        $this->expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
        $this->expected->setCurrentGameTime(Duration::fromComponents(0, 0, 0, 14, 51));
        $this->expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());
        $this->expected->setCompetitionScore($score);

        $this->reader = new LiveScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadWithPeriods()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period.xml');

        $result = $this->reader->read(
            'football',
            'NFL',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/football/NFL/livescores/livescores_12345.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadWithPeriodsOnCompleteEvent()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period-complete.xml');

        $result = $this->reader->read(
            'football',
            'NFL',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/football/NFL/livescores/livescores_12345.xml');

        $expected = new PeriodResult;
        $expected->setCompetitionStatus(CompetitionStatus::COMPLETE());

        $score = new PeriodScore;
        $score->add(new Period(PeriodType::PERIOD(), 3,  7));
        $score->add(new Period(PeriodType::PERIOD(), 3,  0));
        $score->add(new Period(PeriodType::PERIOD(), 10, 3));
        $score->add(new Period(PeriodType::PERIOD(), 3,  5));

        $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testReadWithSpecialPeriods()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period-special.xml');

        $result = $this->reader->read(
            'hockey',
            'NHL',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/hockey/NHL/livescores/livescores_12345.xml');

        $scope1 = new Period(PeriodType::PERIOD(),   2, 0);
        $scope2 = new Period(PeriodType::PERIOD(),   0, 0);
        $scope3 = new Period(PeriodType::PERIOD(),   1, 3);
        $scope4 = new Period(PeriodType::OVERTIME(), 0, 0);
        $scope5 = new Period(PeriodType::SHOOTOUT(), 0, 0);
        $scope6 = new Period(PeriodType::SHOOTOUT(), 0, 1);
        $scope7 = new Period(PeriodType::SHOOTOUT(), 0, 0);

        $expected = new PeriodResult;
        $expected->setCurrentScope($scope7);
        $expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
        $expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());

        $score = new PeriodScore;
        $score->add($scope1);
        $score->add($scope2);
        $score->add($scope3);
        $score->add($scope4);
        $score->add($scope5);
        $score->add($scope6);
        $score->add($scope7);

        $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testReadWithInnings()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-inning.xml');

        $result = $this->reader->read(
            'baseball',
            'MLB',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/baseball/MLB/livescores/livescores_12345.xml');

        $scope1 = new Inning(0, 0);
        $scope2 = new Inning(0, 1);

        $expected = new InningResult;
        $expected->setCurrentScope($scope2);
        $expected->setCurrentScopeStatus(ScopeStatus::IN_PROGRESS());
        $expected->setCurrentInningSubType(InningSubType::BOTTOM());
        $expected->setCompetitionStatus(CompetitionStatus::IN_PROGRESS());

        $score = new InningScore(
            3, // home team hits
            2, // away team hits
            0, // home team errors
            0  // away team errors
        );

        $score->add($scope1);
        $score->add($scope2);

        $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testReadWithInningsOnCompleteEvent()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-inning-complete.xml');

        $result = $this->reader->read(
            'baseball',
            'MLB',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/baseball/MLB/livescores/livescores_12345.xml');

        $expected = new InningResult;
        $expected->setCompetitionStatus(CompetitionStatus::COMPLETE());

        $score = new InningScore(
            10, // home team hits
            15, // away team hits
            1,  // home team errors
            1   // away team errors
        );

        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 1));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(1, 2));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 1));
        $score->add(new Inning(0, 3));
        $score->add(new Inning(0, 0));

        $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }
    public function testReadWithUnsupportedCompetition()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The provided competition could not be handled by any of the known live score factories.'
        );

        $this->reader->read(
            'unknown-sport',
            'unknown-league',
            '/path/to/sport:12345'
        );
    }

    public function testReadAtomEntry()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/football/NFL/livescores/livescores_12345.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/livescores/livescores_12345.xml');

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadAtomEntryFailure()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported atom entry.'
        );

        $this->reader->readAtomEntry(
            new AtomEntry(
                '<atom-url>',
                '<atom-resource>',
                ['foo' => 'bar'],
                DateTime::fromUnixTime(0)
            )
        );
    }

    public function testSupportsAtomEntry()
    {
        $this->assertFalse(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<atom-url>',
                    '<atom-resource>',
                    ['foo' => 'bar'],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertFalse(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<atom-url>',
                    '<atom-resource>',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );

        $this->assertTrue(
            $this->reader->supportsAtomEntry(
                new AtomEntry(
                    '<url>',
                    '/sport/v2/football/NFL/livescores/livescores_12345.xml',
                    [],
                    DateTime::fromUnixTime(0)
                )
            )
        );
    }

    public function testSupportsAtomEntryParameters()
    {
        $entry = new AtomEntry(
            '<atom-url>',
            '/sport/v2/football/NFL/livescores/livescores_12345.xml',
            [],
            DateTime::fromUnixTime(0)
        );

        $parameters = [];

        $this->assertTrue(
            $this->reader->supportsAtomEntry($entry, $parameters)
        );

        $this->assertSame(
            [
                'sport'         => 'football',
                'league'        => 'NFL',
                'competitionId' => '/sport/football/competition:12345',
            ],
            $parameters
        );
    }
}
