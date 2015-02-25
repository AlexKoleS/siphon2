<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\LiveScore\Innings\Innings;
use Icecave\Siphon\LiveScore\Period\Period;
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
        $this->setUpXmlReader('LiveScore/livescores-period.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type' => 'period',
                    'home' => [
                        'score' => 3,
                    ],
                    'away' => [
                        'score' => 7,
                    ],
                ],
                (object) [
                    'type' => 'period',
                    'home' => [
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
        $this->setUpXmlReader('LiveScore/livescores-period-special.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type' => 'period',
                    'home' => [
                        'score' => 2,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type' => 'period',
                    'home' => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type' => 'period',
                    'home' => [
                        'score' => 1,
                    ],
                    'away' => [
                        'score' => 3,
                    ],
                ],
                (object) [
                    'type' => 'overtime',
                    'home' => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type' => 'shootout',
                    'home' => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 0,
                    ],
                ],
                (object) [
                    'type' => 'shootout',
                    'home' => [
                        'score' => 0,
                    ],
                    'away' => [
                        'score' => 1,
                    ],
                ],
                (object) [
                    'type' => 'shootout',
                    'home' => [
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
        $this->setUpXmlReader('LiveScore/livescores-innings.xml');

        $xml = $this
            ->xmlReader()
            ->mock()
            ->read('<resource>');

        $result = $this->aggregator->extract($xml);

        $this->assertEquals(
            [
                (object) [
                    'type' => 'inning',
                    'home' => [
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
                    'type' => 'inning',
                    'home' => [
                        'errors' => 0,
                        'hits'   => 2,
                        'runs'   => 0,
                    ],
                    'away' => [
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
