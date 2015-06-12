<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\CompetitionRef;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class LiveScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new LiveScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Score/livescores.xml');

        $response = $this
            ->reader
            ->read(
                new LiveScoreRequest(
                    Sport::NHL(),
                    23816
                )
            );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/hockey/NHL/livescores/livescores_23816.xml');

        $currentPeriod = new Period(PeriodType::SHOOTOUT(), 3, 0, 0);

        $expected = new LiveScoreResponse(
            new CompetitionRef(
                '/sport/hockey/competition:23816',
                CompetitionStatus::IN_PROGRESS(),
                DateTime::fromIsoString('2009-01-28T14:00:00.000-05:00'),
                Sport::NHL(),
                new TeamRef('/sport/hockey/team:7', 'Pittsburgh'),
                new TeamRef('/sport/hockey/team:6', 'NY Rangers')
            ),
            new Score(
                [
                    new Period(PeriodType::PERIOD(),   1, 2, 0),
                    new Period(PeriodType::PERIOD(),   2, 0, 0),
                    new Period(PeriodType::PERIOD(),   3, 1, 3),
                    new Period(PeriodType::OVERTIME(), 1, 0, 0),
                    new Period(PeriodType::SHOOTOUT(), 1, 0, 0),
                    new Period(PeriodType::SHOOTOUT(), 2, 0, 1),
                    $currentPeriod,
                ]
            )
        );

        $expected->setCurrentPeriod($currentPeriod);

        $expected->setGameTime(
            Duration::fromComponents(0, 0, 0, 2, 55)
        );

        $this->assertEquals(
            $expected,
            $response
        );
    }

    public function testReadBaseball()
    {
        $this->setUpXmlReader('Score/livescores-baseball.xml');

        $currentPeriod = new Period(PeriodType::EXTRA_INNING(), 1, 0, 0);

        $expected = new LiveScoreResponse(
            new CompetitionRef(
                '/sport/baseball/competition:288425',
                CompetitionStatus::IN_PROGRESS(),
                DateTime::fromIsoString('2009-01-28T11:00:00.000-05:00'),
                Sport::MLB(),
                new TeamRef('/sport/baseball/team:2968', 'Arizona'),
                new TeamRef('/sport/baseball/team:2975', 'St. Louis')
            ),
            new Score(
                [
                    new Period(PeriodType::INNING(),       1, 0, 0),
                    new Period(PeriodType::INNING(),       2, 0, 1),
                    new Period(PeriodType::INNING(),       3, 0, 0),
                    new Period(PeriodType::INNING(),       4, 1, 2),
                    new Period(PeriodType::INNING(),       5, 0, 0),
                    new Period(PeriodType::INNING(),       6, 0, 0),
                    new Period(PeriodType::INNING(),       7, 0, 1),
                    new Period(PeriodType::INNING(),       8, 0, 3),
                    new Period(PeriodType::INNING(),       9, 6, 0),
                    $currentPeriod,
                ]
            )
        );

        $expected->setCurrentPeriod($currentPeriod);

        $this->assertEquals(
            $expected,
            $this->reader->read(
                new LiveScoreRequest(
                    Sport::MLB(),
                    288425
                )
            )
        );
    }

    public function testReadDoesNotSetCurrentPeriodWhenCompetitionHasEnded()
    {
        $this->setUpXmlReader('Score/livescores-complete.xml');

        $response = $this->reader->read(
            new LiveScoreRequest(
                Sport::NBA(),
                778888
            )
        );

        $this->assertSame(
            CompetitionStatus::COMPLETE(),
            $response->competition()->status()
        );

        $this->assertNull(
            $response->currentPeriod()
        );
    }

    public function testReadNormalizesPeriodType()
    {
        $this->setUpXmlReader('Score/livescores-complete.xml');

        $response = $this->reader->read(
            new LiveScoreRequest(
                Sport::NBA(),
                778888
            )
        );

        // All period types should be quarter (rather than just 'period') ...
        foreach ($response->score() as $period) {
            $this->assertSame(
                PeriodType::QUARTER(),
                $period->type()
            );
        }
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
            $this->reader->isSupported(
                new LiveScoreRequest(
                    Sport::MLB(),
                    288425
                )
            )
        );

        $this->assertFalse(
            $this->reader->isSupported(
                Phony::mock(RequestInterface::class)->mock()
            )
        );
    }
}
