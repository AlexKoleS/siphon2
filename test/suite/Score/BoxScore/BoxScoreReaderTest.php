<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Player\Statistics;
use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class BoxScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->expected = new Result;
        $this->expected->setPlayerStatistics(
            [
                // home team ...
                new Statistics(
                    '/sport/football/player:27853',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'  => 1,
                            'games_started' => 1,
                        ]
                    ]
                ),
                new Statistics(
                    '/sport/football/player:347',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'             => 1,
                            'defense_assisted_tackles' => 1,
                        ]
                    ]
                ),
                new Statistics(
                    '/sport/football/player:2753',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'              => 1,
                            'games_started'             => 1,
                            'rushing_plays'             => 1,
                            'rushing_net_yards'         => 3,
                            'rushing_longest_yards'     => 3,
                            'passing_plays_attempted'   => 43,
                            'passing_plays_completed'   => 33,
                            'passing_gross_yards'       => 405,
                            'passing_net_yards'         => 363,
                            'passing_longest_yards'     => 34,
                            'passing_plays_intercepted' => 2,
                            'passing_plays_sacked'      => 4,
                            'passing_sacked_yards'      => 42,
                            'passing_touchdowns'        => 1,
                            'total_touchdowns'          => 1,
                            'starter_games_won'         => 1,
                            'passer_rating'             => 89.583336000000003,
                        ]
                    ]
                ),

                // away team ...
                new Statistics(
                    '/sport/football/player:12674',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'              => 1,
                            'games_started'             => 1,
                            'defense_solo_tackles'      => 8,
                            'defense_assisted_tackles'  => 4,
                            'defense_fumble_recoveries' => 1,
                        ]
                    ]
                ),
                new Statistics(
                    '/sport/football/player:729',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'             => 1,
                            'games_started'            => 1,
                            'defense_solo_tackles'     => 2,
                            'defense_assisted_tackles' => 1,
                        ]
                    ]
                ),
                new Statistics(
                    '/sport/football/player:9058',
                    '2009-2010',
                    [
                        'game-stats' => [
                            'games_played'  => 1,
                            'games_started' => 1,
                        ]
                    ]
                ),
            ]
        );

        $score = new PeriodScore;
        $score->add(new Period(PeriodType::PERIOD(),   0, 0));
        $score->add(new Period(PeriodType::PERIOD(),   7, 7));
        $score->add(new Period(PeriodType::PERIOD(),   0, 0));
        $score->add(new Period(PeriodType::PERIOD(),   3, 3));
        $score->add(new Period(PeriodType::OVERTIME(), 3, 0));

        $this->expected->setCompetitionScore($score);

        $this->reader = new BoxScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testReadWithPeriodsOnCompleteEvent()
    {
        $this->setUpXmlReader('Score/BoxScore/boxscores-period-complete.xml');

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

        $this->assertEquals(
            $this->expected,
            $result
        );
    }

    public function testReadWithInningsOnCompleteEvent()
    {
        $this->setUpXmlReader('Score/BoxScore/boxscores-inning-complete.xml');

        $result = $this->reader->read(
            'baseball',
            'MLB',
            '2009',
            '/path/to/sport:12345'
        );

        $this
            ->xmlReader()
            ->read
            ->calledWith('/sport/v2/baseball/MLB/boxscores/2009/boxscore_MLB_12345.xml');

        // TODO: test player stats
        $result->setPlayerStatistics([]);

        $expected = new Result;

        $score = new InningScore(
            10, // home team hits
            8,  // away team hits
            0,  // home team errors
            1   // away team errors
        );

        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 1));
        $score->add(new Inning(2, 0));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(0, 0));
        $score->add(new Inning(1, 2));
        $score->add(new Inning(2, 0));
        $score->add(new Inning(0, 0));

        $expected->setCompetitionScore($score);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testReadWithInningsOnCompleteEventPlayerStatistics()
    {
        // This test should be removed when the test above handles testing of the player stats
        $this->markTestIncomplete(
            'Player statistics have not been chekced for innings-based box scores.'
        );
    }

    public function testReadWithUnsupportedCompetition()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The provided competition could not be handled by any of the known score factories.'
        );

        $this->reader->read(
            'unknown-sport',
            'unknown-league',
            '2009',
            '/path/to/sport:12345'
        );
    }

    public function testReadAtomEntry()
    {
        $this->setUpXmlReader('Score/BoxScore/boxscores-period-complete.xml');

        $result = $this->reader->readAtomEntry(
            new AtomEntry(
                '<url>',
                '/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml',
                [],
                DateTime::fromUnixTime(0)
            )
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml');

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
                    '/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml',
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
            '/sport/v2/football/NFL/boxscores/2009-2010/boxscore_NFL_12345.xml',
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
                'season'        => '2009-2010',
                'competitionId' => '/sport/football/competition:12345',
            ],
            $parameters
        );
    }
}
