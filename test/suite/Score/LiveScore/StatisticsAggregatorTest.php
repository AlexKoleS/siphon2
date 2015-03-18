<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Score\LiveScore\Period\Period;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class StatisticsAggregatorTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->aggregator = new StatisticsAggregator;
    }

    public function testExtractWithPeriods()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type'   => 'period',
                    'number' => 1,
                    'home'   => [
                        'score' => 3,
                    ],
                    'away' => [
                        'score' => 7,
                    ],
                ],
                (object) [
                    'type'   => 'period',
                    'number' => 2,
                    'home'   => [
                        'score' => 3,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
            ],
            $result
        );
    }

    public function testExtractWithSpecialPeriods()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-period-special.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type'   => 'period',
                    'number' => 1,
                    'home'   => [
                        'score' => 2,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type'   => 'period',
                    'number' => 2,
                    'home'   => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type'   => 'period',
                    'number' => 3,
                    'home'   => [
                        'score' => 1,
                    ],
                    'away' => [
                        'score' => 3,
                    ],
                ],
                (object) [
                    'type'   => 'overtime',
                    'number' => 1,
                    'home'   => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type'   => 'shootout',
                    'number' => 1,
                    'home'   => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type'   => 'shootout',
                    'number' => 2,
                    'home'   => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 1,
                    ],
                ],
                (object) [
                    'type'   => 'shootout',
                    'number' => 3,
                    'home'   => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
            ],
            $result
        );
    }

    public function testExtractWithInnings()
    {
        $this->setUpXmlReader('Score/LiveScore/livescores-inning.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type'   => 'inning',
                    'number' => 1,
                    'home'   => [
                        'errors' => 0,
                        'hits'   => 1,
                        'runs'   => 0,
                    ],
                    'away' => [
                        'errors' => 0,
                        'hits'   => 0,
                        'runs'   => 0,
                    ],
                ],
                (object) [
                    'type'   => 'inning',
                    'number' => 2,
                    'home'   => [
                        'errors' => 0,
                        'hits'   => 2,
                        'runs'   => 0,
                    ],
                    'away'   => [
                        'errors' => 0,
                        'hits'   => 2,
                        'runs'   => 1,
                    ],
                ],
            ],
            $result
        );
    }
}
