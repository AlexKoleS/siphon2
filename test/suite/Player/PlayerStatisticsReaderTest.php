<?php
namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsGroup;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class PlayerStatisticsReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new PlayerStatisticsRequest(
            Sport::NFL(),
            '2009-2010',
            23
        );

        $this->response = new PlayerStatisticsResponse(
            Sport::NFL(),
            new Season(
                '/sport/football/season:74',
                '2009-2010',
                Date::fromIsoString('2009-08-01'),
                Date::fromIsoString('2010-02-28')
            ),
            new TeamRef(
                '/sport/football/team:23',
                'Arizona'
            )
        );

        $this->reader = new PlayerStatisticsReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->response->add(
            new Player('/sport/football/player:16721', 'Hamza', 'Abdullah'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'                       => 1,
                            'defense_solo_tackles'               => 7,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 1,
                        ]
                    ),
                    new StatisticsGroup(
                        'post-season-stats',
                        [
                            'season-phase-from' => 'Wildcard',
                            'season-phase-to'   => 'Superbowl',
                        ],
                        [
                            'games_played'                       => 2,
                            'defense_solo_tackles'               => 1,
                            'defense_assisted_tackles'           => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new Player('/sport/football/player:6686', 'Michael', 'Adams'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                             'games_played'                       => 16,
                             'games_started'                      => 1,
                             'defense_interceptions'              => 1,
                             'defense_interception_yards'         => 17,
                             'defense_solo_tackles'               => 25,
                             'defense_assisted_tackles'           => 3,
                             'defense_special_teams_solo_tackles' => 11,
                             'defense_pass_defenses'              => 4,
                        ]
                    ),
                    new StatisticsGroup(
                        'post-season-stats',
                        [
                            'season-phase-from' => 'Wildcard',
                            'season-phase-to'   => 'Superbowl',
                        ],
                        [
                            'games_played'                       => 2,
                            'defense_solo_tackles'               => 2,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_sacks'                      => 1.0,
                            'defense_sack_yards'                 => 7.0,
                            'defense_forced_fumbles'             => 1,
                        ]
                    ),
                ]
            )
        );

        $this->setUpXmlReader('Player/stats.xml');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/player-stats/2009-2010/player_stats_23_NFL.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
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
            $this->reader->isSupported($this->request)
        );

        $this->assertFalse(
            $this->reader->isSupported(
                Phony::mock(RequestInterface::class)->mock()
            )
        );
    }
}
