<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

/**
 * @covers Icecave\Siphon\Player\StatisticsReader
 * @covers Icecave\Siphon\Player\StatisticsFactory
 */
class StatisticsReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new StatisticsReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/player-stats.xml');

        $result = $this->reader->read(
            'football',
            'NFL',
            '2009-2010',
            '/sport/baseball/team:12345'
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/player-stats/2009-2010/player_stats_12345_NFL.xml');

        $this->assertEquals(
            [
                new Statistics(
                    '/sport/football/player:16721',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 1,
                            'defense_solo_tackles'               => 7,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'defense_solo_tackles'     => 1,
                            'defense_assisted_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:6686',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 16,
                            'games_started'                      => 1,
                            'defense_interceptions'              => 1,
                            'defense_interception_yards'         => 17,
                            'defense_solo_tackles'               => 25,
                            'defense_assisted_tackles'           => 3,
                            'defense_special_teams_solo_tackles' => 11,
                            'defense_pass_defenses'              => 4,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'defense_solo_tackles'               => 2,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_sacks'                      => 1,
                            'defense_sack_yards'                 => 7,
                            'defense_forced_fumbles'             => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:35835',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'             => 1,
                            'defense_solo_tackles'     => 1,
                            'defense_assisted_tackles' => 1,
                            'defense_sacks'            => 1,
                            'defense_sack_yards'       => 6,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:333',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 10,
                            'receiving_targeted'        => 12,
                            'receiving_receptions'      => 7,
                            'receiving_yards'           => 61,
                            'receiving_longest_yards'   => 16,
                            'fumbles'                   => 1,
                            'fumbles_lost'              => 1,
                            'receiving_touchdowns'      => 1,
                            'total_touchdowns'          => 1,
                            'defense_misc_solo_tackles' => 3,
                            'defense_fumble_recoveries' => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 2,
                            'games_started'           => 2,
                            'receiving_targeted'      => 1,
                            'receiving_receptions'    => 1,
                            'receiving_yards'         => 8,
                            'receiving_longest_yards' => 8,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:1253',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 6,
                            'defense_solo_tackles'               => 2,
                            'defense_special_teams_solo_tackles' => 4,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 1,
                            'defense_solo_tackles'      => 2,
                            'defense_assisted_tackles'  => 2,
                            'defense_fumble_recoveries' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:534',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 15,
                            'games_started'             => 5,
                            'defense_solo_tackles'      => 9,
                            'defense_assisted_tackles'  => 1,
                            'defense_sacks'             => 6,
                            'defense_sack_yards'        => 30,
                            'defense_fumble_recoveries' => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'defense_solo_tackles'               => 2,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_sacks'                      => 2,
                            'defense_sack_yards'                 => 8,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:204',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 15,
                            'games_started'             => 15,
                            'rushing_plays'             => 3,
                            'rushing_net_yards'         => 12,
                            'rushing_longest_yards'     => 5,
                            'receiving_targeted'        => 127,
                            'receiving_receptions'      => 84,
                            'receiving_yards'           => 1024,
                            'receiving_longest_yards'   => 44,
                            'fumbles'                   => 3,
                            'fumbles_lost'              => 2,
                            'rushing_touchdowns'        => 1,
                            'receiving_touchdowns'      => 4,
                            'total_touchdowns'          => 5,
                            'defense_misc_solo_tackles' => 3,
                            'defense_fumble_recoveries' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:16948',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'             => 16,
                            'defense_solo_tackles'     => 14,
                            'defense_assisted_tackles' => 4,
                            'defense_sacks'            => 2,
                            'defense_sack_yards'       => 12,
                            'defense_pass_defenses'    => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'defense_solo_tackles'      => 3,
                            'defense_assisted_tackles'  => 1,
                            'defense_fumble_recoveries' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:2735',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 15,
                            'games_started'             => 6,
                            'rushing_plays'             => 2,
                            'rushing_net_yards'         => 44,
                            'rushing_longest_yards'     => 25,
                            'receiving_targeted'        => 82,
                            'receiving_receptions'      => 55,
                            'receiving_yards'           => 712,
                            'receiving_longest_yards'   => 45,
                            'fumbles'                   => 2,
                            'fumbles_lost'              => 1,
                            'receiving_touchdowns'      => 3,
                            'total_touchdowns'          => 3,
                            'punt_returns'              => 38,
                            'punt_return_yards'         => 253,
                            'punt_return_longest_yards' => 64,
                            'punt_return_faircatches'   => 11,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'rushing_plays'             => 1,
                            'rushing_net_yards'         => 28,
                            'rushing_longest_yards'     => 28,
                            'receiving_targeted'        => 16,
                            'receiving_receptions'      => 11,
                            'receiving_yards'           => 177,
                            'receiving_longest_yards'   => 26,
                            'fumbles'                   => 1,
                            'receiving_touchdowns'      => 1,
                            'total_touchdowns'          => 1,
                            'punt_returns'              => 1,
                            'punt_return_yards'         => 6,
                            'punt_return_longest_yards' => 6,
                            'punt_return_faircatches'   => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:1786',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 15,
                            'games_started'             => 4,
                            'defense_fumble_recoveries' => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'defense_fumble_recoveries' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:5493',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:23983',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:12958',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:10111',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'  => 16,
                            'games_started' => 16,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'defense_misc_solo_tackles' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:1379',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 16,
                            'games_started'                          => 1,
                            'defense_interceptions'                  => 1,
                            'defense_interception_yards'             => 85,
                            'defense_solo_tackles'                   => 16,
                            'defense_special_teams_assisted_tackles' => 1,
                            'defense_pass_defenses'                  => 3,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'defense_solo_tackles'     => 3,
                            'defense_assisted_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:10284',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:3279',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:18298',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 16,
                            'games_started'                      => 15,
                            'defense_solo_tackles'               => 35,
                            'defense_assisted_tackles'           => 11,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_sacks'                      => 7,
                            'defense_sack_yards'                 => 50.5,
                            'defense_forced_fumbles'             => 1,
                            'defense_pass_defenses'              => 5,
                        ],
                        'post-season-stats' => [
                            'games_played'         => 2,
                            'games_started'        => 2,
                            'defense_solo_tackles' => 5,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:10332',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:2556',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:34882',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:19198',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:16918',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'               => 16,
                            'games_started'              => 16,
                            'defense_interceptions'      => 1,
                            'defense_interception_yards' => 11,
                            'defense_solo_tackles'       => 90,
                            'defense_assisted_tackles'   => 20,
                            'defense_sacks'              => 1,
                            'defense_sack_yards'         => 4,
                            'defense_forced_fumbles'     => 1,
                            'defense_pass_defenses'      => 4,
                        ],
                        'post-season-stats' => [
                            'games_played'                 => 2,
                            'games_started'                => 2,
                            'fumbles_recovered_touchdowns' => 1,
                            'total_touchdowns'             => 1,
                            'defense_solo_tackles'         => 12,
                            'defense_assisted_tackles'     => 2,
                            'defense_forced_fumbles'       => 1,
                            'defense_fumble_recoveries'    => 1,
                            'defense_pass_defenses'        => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:18680',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:19377',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 11,
                            'defense_solo_tackles'                   => 8,
                            'defense_assisted_tackles'               => 2,
                            'defense_special_teams_solo_tackles'     => 6,
                            'defense_special_teams_assisted_tackles' => 1,
                            'defense_sacks'                          => 2,
                            'defense_sack_yards'                     => 8,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'defense_special_teams_solo_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:16917',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'               => 16,
                            'games_started'              => 16,
                            'fumbles'                    => 1,
                            'defense_interceptions'      => 1,
                            'defense_interception_yards' => 3,
                            'defense_solo_tackles'       => 42,
                            'defense_assisted_tackles'   => 9,
                            'defense_sacks'              => 7,
                            'defense_sack_yards'         => 54,
                            'defense_fumble_recoveries'  => 1,
                            'defense_pass_defenses'      => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'games_started'            => 2,
                            'defense_solo_tackles'     => 1,
                            'defense_assisted_tackles' => 1,
                            'defense_sacks'            => 1,
                            'defense_sack_yards'       => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:10151',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 9,
                            'receiving_targeted'                     => 24,
                            'receiving_receptions'                   => 17,
                            'receiving_yards'                        => 214,
                            'receiving_longest_yards'                => 29,
                            'receiving_touchdowns'                   => 1,
                            'total_touchdowns'                       => 1,
                            'defense_special_teams_solo_tackles'     => 1,
                            'defense_special_teams_assisted_tackles' => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 2,
                            'receiving_targeted'      => 17,
                            'receiving_receptions'    => 14,
                            'receiving_yards'         => 145,
                            'receiving_longest_yards' => 16,
                            'receiving_touchdowns'    => 2,
                            'total_touchdowns'        => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:29249',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:13958',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'         => 2,
                            'defense_solo_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:23580',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:15373',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 16,
                            'receiving_targeted'        => 153,
                            'receiving_receptions'      => 97,
                            'receiving_yards'           => 1092,
                            'receiving_longest_yards'   => 34,
                            'receiving_touchdowns'      => 13,
                            'total_touchdowns'          => 13,
                            'defense_misc_solo_tackles' => 6,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 2,
                            'games_started'           => 2,
                            'receiving_targeted'      => 16,
                            'receiving_receptions'    => 12,
                            'receiving_yards'         => 159,
                            'receiving_longest_yards' => 33,
                            'fumbles'                 => 1,
                            'fumbles_lost'            => 1,
                            'receiving_touchdowns'    => 2,
                            'total_touchdowns'        => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:7722',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:787',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:2865',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:1121',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 12,
                            'games_started'             => 12,
                            'defense_misc_solo_tackles' => 1,
                            'defense_fumble_recoveries' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:40154',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:21836',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:19151',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 16,
                            'punting_plays'                      => 86,
                            'punting_gross_yards'                => 4045,
                            'punting_touchbacks'                 => 3,
                            'punting_inside_twenty'              => 42,
                            'punting_longest_yards'              => 64,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_forced_fumbles'             => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'          => 2,
                            'punting_plays'         => 7,
                            'punting_gross_yards'   => 287,
                            'punting_longest_yards' => 51,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:7374',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:488',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 16,
                            'games_started'                      => 14,
                            'defense_solo_tackles'               => 55,
                            'defense_assisted_tackles'           => 17,
                            'defense_special_teams_solo_tackles' => 2,
                            'defense_sacks'                      => 5,
                            'defense_sack_yards'                 => 22.5,
                            'defense_forced_fumbles'             => 2,
                            'defense_pass_defenses'              => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'games_started'            => 2,
                            'defense_solo_tackles'     => 4,
                            'defense_assisted_tackles' => 1,
                            'defense_pass_defenses'    => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:302',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'             => 14,
                            'games_started'            => 13,
                            'defense_solo_tackles'     => 50,
                            'defense_assisted_tackles' => 13,
                            'defense_forced_fumbles'   => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'         => 1,
                            'games_started'        => 1,
                            'defense_solo_tackles' => 4,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:10146',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 14,
                            'games_started'                          => 2,
                            'defense_solo_tackles'                   => 6,
                            'defense_assisted_tackles'               => 1,
                            'defense_special_teams_solo_tackles'     => 3,
                            'defense_special_teams_assisted_tackles' => 3,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:24833',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 16,
                            'rushing_plays'             => 143,
                            'rushing_net_yards'         => 598,
                            'rushing_longest_yards'     => 50,
                            'receiving_targeted'        => 80,
                            'receiving_receptions'      => 63,
                            'receiving_yards'           => 428,
                            'receiving_longest_yards'   => 23,
                            'fumbles'                   => 5,
                            'fumbles_lost'              => 4,
                            'rushing_touchdowns'        => 8,
                            'total_touchdowns'          => 8,
                            'defense_misc_solo_tackles' => 3,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'rushing_plays'             => 13,
                            'rushing_net_yards'         => 106,
                            'rushing_longest_yards'     => 70,
                            'receiving_targeted'        => 7,
                            'receiving_receptions'      => 6,
                            'receiving_yards'           => 48,
                            'receiving_longest_yards'   => 13,
                            'fumbles'                   => 1,
                            'rushing_touchdowns'        => 2,
                            'total_touchdowns'          => 2,
                            'defense_fumble_recoveries' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:354',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:10860',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:8433',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 11,
                            'defense_solo_tackles'                   => 4,
                            'defense_special_teams_solo_tackles'     => 1,
                            'defense_special_teams_assisted_tackles' => 4,
                            'defense_sacks'                          => 2,
                            'defense_sack_yards'                     => 11,
                        ],
                        'post-season-stats' => [
                            'games_played' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:10251',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:20759',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 10,
                            'games_started'                      => 1,
                            'defense_solo_tackles'               => 17,
                            'defense_assisted_tackles'           => 1,
                            'defense_special_teams_solo_tackles' => 2,
                            'defense_pass_defenses'              => 1,
                        ],
                        'post-season-stats' => [
                            'games_played' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:33842',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:19504',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played' => 3,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:16307',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:480',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'            => 14,
                            'games_started'           => 3,
                            'receiving_targeted'      => 6,
                            'receiving_receptions'    => 4,
                            'receiving_yards'         => 20,
                            'receiving_longest_yards' => 8,
                        ],
                        'post-season-stats' => [
                            'games_played' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:557',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 16,
                            'defense_special_teams_solo_tackles'     => 4,
                            'defense_special_teams_assisted_tackles' => 1,
                        ],
                        'post-season-stats' => [
                            'games_played' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:3276',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 8,
                            'games_started'             => 1,
                            'rushing_plays'             => 9,
                            'rushing_net_yards'         => -6,
                            'rushing_longest_yards'     => 1,
                            'passing_plays_attempted'   => 77,
                            'passing_plays_completed'   => 51,
                            'passing_gross_yards'       => 447,
                            'passing_net_yards'         => 435,
                            'passing_longest_yards'     => 28,
                            'passing_plays_intercepted' => 3,
                            'passing_plays_sacked'      => 2,
                            'passing_sacked_yards'      => 12,
                            'fumbles'                   => 1,
                            'starter_games_lost'        => 1,
                            'passer_rating'             => 64.583330000000004,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 1,
                            'passing_plays_attempted' => 10,
                            'passing_plays_completed' => 7,
                            'passing_gross_yards'     => 61,
                            'passing_net_yards'       => 61,
                            'passing_longest_yards'   => 16,
                            'passer_rating'           => 85.833336000000003,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:17799',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:33908',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:10532',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'       => 1,
                            'receiving_targeted' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:15143',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'  => 16,
                            'games_started' => 16,
                        ],
                        'post-season-stats' => [
                            'games_played'  => 2,
                            'games_started' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:40155',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:8804',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 16,
                            'defense_solo_tackles'      => 64,
                            'defense_assisted_tackles'  => 5,
                            'defense_fumble_recoveries' => 1,
                            'defense_pass_defenses'     => 15,
                        ],
                        'post-season-stats' => [
                            'games_played'          => 2,
                            'games_started'         => 2,
                            'defense_solo_tackles'  => 13,
                            'defense_pass_defenses' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:8286',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:29448',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:1780',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 13,
                            'receiving_targeted'                     => 1,
                            'defense_special_teams_solo_tackles'     => 13,
                            'defense_special_teams_assisted_tackles' => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'defense_special_teams_solo_tackles' => 2,
                            'defense_forced_fumbles'             => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:3626',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                        => 2,
                            'extra_point_kicks_attempted'         => 8,
                            'extra_point_kicks_succeeded'         => 8,
                            'field_goals_attempted'               => 2,
                            'field_goals_succeeded'               => 2,
                            'field_goals_succeeded_longest_yards' => 48,
                        ],
                        'regular-season-field-goal-stats' => [
                            'field_goals_succeeded' => 1,
                            'field_goals_attempted' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:1558',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'             => 13,
                            'games_started'            => 13,
                            'defense_solo_tackles'     => 35,
                            'defense_assisted_tackles' => 12,
                            'defense_sacks'            => 4.5,
                            'defense_sack_yards'       => 22.5,
                            'defense_pass_defenses'    => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'         => 2,
                            'games_started'        => 2,
                            'defense_solo_tackles' => 6,
                            'defense_sacks'        => 1,
                            'defense_sack_yards'   => 3,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:12964',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:3225',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'            => 9,
                            'games_started'           => 7,
                            'receiving_targeted'      => 16,
                            'receiving_receptions'    => 12,
                            'receiving_yards'         => 146,
                            'receiving_longest_yards' => 28,
                            'receiving_touchdowns'    => 2,
                            'total_touchdowns'        => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 2,
                            'games_started'           => 2,
                            'receiving_targeted'      => 3,
                            'receiving_receptions'    => 3,
                            'receiving_yards'         => 42,
                            'receiving_longest_yards' => 22,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:13717',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:1934',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:4138',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:17244',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:1941',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                        => 14,
                            'extra_point_kicks_attempted'         => 38,
                            'extra_point_kicks_succeeded'         => 37,
                            'field_goals_attempted'               => 17,
                            'field_goals_succeeded'               => 16,
                            'field_goals_succeeded_longest_yards' => 48,
                            'defense_special_teams_solo_tackles'  => 5,
                        ],
                        'regular-season-field-goal-stats' => [
                            'field_goals_succeeded' => 6,
                            'field_goals_attempted' => 7,
                        ],
                        'post-season-stats' => [
                            'games_played'                        => 2,
                            'extra_point_kicks_attempted'         => 8,
                            'extra_point_kicks_succeeded'         => 8,
                            'field_goals_attempted'               => 3,
                            'field_goals_succeeded'               => 1,
                            'field_goals_succeeded_longest_yards' => 23,
                        ],
                        'post-season-field-goal-stats' => [
                            'field_goals_attempted' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:4368',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:1131',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 15,
                            'defense_solo_tackles'      => 17,
                            'defense_assisted_tackles'  => 11,
                            'defense_sacks'             => 1,
                            'defense_sack_yards'        => 7,
                            'defense_fumble_recoveries' => 1,
                            'defense_pass_defenses'     => 3,
                        ],
                        'post-season-stats' => [
                            'games_played'          => 2,
                            'games_started'         => 2,
                            'defense_solo_tackles'  => 1,
                            'defense_pass_defenses' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:35815',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                      => 16,
                            'games_started'                     => 16,
                            'interceptions_returned_touchdowns' => 1,
                            'total_touchdowns'                  => 1,
                            'defense_interceptions'             => 6,
                            'defense_interception_yards'        => 77,
                            'defense_solo_tackles'              => 48,
                            'defense_assisted_tackles'          => 2,
                            'defense_forced_fumbles'            => 3,
                            'defense_pass_defenses'             => 24,
                        ],
                        'post-season-stats' => [
                            'games_played'               => 2,
                            'games_started'              => 2,
                            'defense_interceptions'      => 1,
                            'defense_interception_yards' => -6,
                            'defense_solo_tackles'       => 5,
                            'defense_pass_defenses'      => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:2260',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 15,
                            'games_started'                      => 15,
                            'rushing_plays'                      => 1,
                            'rushing_net_yards'                  => 9,
                            'rushing_longest_yards'              => 9,
                            'fumbles'                            => 1,
                            'punt_returns'                       => 6,
                            'punt_return_yards'                  => 55,
                            'punt_return_longest_yards'          => 27,
                            'punt_return_faircatches'            => 2,
                            'defense_interceptions'              => 4,
                            'defense_interception_yards'         => 71,
                            'defense_solo_tackles'               => 60,
                            'defense_assisted_tackles'           => 11,
                            'defense_special_teams_solo_tackles' => 1,
                            'defense_sacks'                      => 1.5,
                            'defense_forced_fumbles'             => 1,
                            'defense_fumble_recoveries'          => 1,
                            'defense_pass_defenses'              => 8,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'games_started'            => 2,
                            'defense_solo_tackles'     => 11,
                            'defense_assisted_tackles' => 5,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:506',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:12151',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:2461',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:14254',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 16,
                            'defense_misc_solo_tackles' => 3,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'defense_misc_solo_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:13854',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:3105',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 7,
                            'games_started'                      => 5,
                            'receiving_targeted'                 => 10,
                            'receiving_receptions'               => 4,
                            'receiving_yards'                    => 38,
                            'receiving_longest_yards'            => 22,
                            'defense_special_teams_solo_tackles' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:512',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 1,
                            'rushing_plays'             => 1,
                            'rushing_net_yards'         => 2,
                            'rushing_longest_yards'     => 2,
                            'passing_plays_attempted'   => 4,
                            'passing_plays_completed'   => 2,
                            'passing_gross_yards'       => 12,
                            'passing_net_yards'         => 12,
                            'passing_longest_yards'     => 9,
                            'passing_plays_intercepted' => 1,
                            'fumbles'                   => 1,
                            'passing_touchdowns'        => 1,
                            'total_touchdowns'          => 1,
                            'defense_fumble_recoveries' => 1,
                            'passer_rating'             => 56.25,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:30573',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 16,
                            'games_started'                          => 2,
                            'rushing_plays'                          => 6,
                            'rushing_net_yards'                      => 15,
                            'rushing_longest_yards'                  => 5,
                            'receiving_targeted'                     => 18,
                            'receiving_receptions'                   => 10,
                            'receiving_yards'                        => 83,
                            'receiving_longest_yards'                => 15,
                            'fumbles'                                => 1,
                            'fumbles_lost'                           => 1,
                            'kickoff_return_touchdowns'              => 1,
                            'receiving_touchdowns'                   => 1,
                            'total_touchdowns'                       => 1,
                            'kickoff_returns'                        => 52,
                            'kickoff_return_yards'                   => 1257,
                            'kickoff_return_longest_yards'           => 99,
                            'defense_special_teams_solo_tackles'     => 17,
                            'defense_special_teams_assisted_tackles' => 3,
                            'defense_misc_solo_tackles'              => 1,
                            'defense_fumble_recoveries'              => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'rushing_plays'                      => 3,
                            'rushing_net_yards'                  => 22,
                            'rushing_longest_yards'              => 18,
                            'receiving_targeted'                 => 4,
                            'receiving_receptions'               => 3,
                            'receiving_yards'                    => 19,
                            'receiving_longest_yards'            => 12,
                            'kickoff_returns'                    => 9,
                            'kickoff_return_yards'               => 224,
                            'kickoff_return_longest_yards'       => 32,
                            'defense_special_teams_solo_tackles' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:27321',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 2,
                            'defense_solo_tackles'               => 3,
                            'defense_special_teams_solo_tackles' => 3,
                        ],
                        'post-season-stats' => [
                            'games_played' => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:39511',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 13,
                            'fumbles'                            => 1,
                            'fumbles_lost'                       => 1,
                            'punt_returns'                       => 1,
                            'defense_interceptions'              => 1,
                            'defense_solo_tackles'               => 7,
                            'defense_special_teams_solo_tackles' => 3,
                            'defense_forced_fumbles'             => 1,
                            'defense_pass_defenses'              => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 1,
                            'defense_solo_tackles'     => 5,
                            'defense_assisted_tackles' => 1,
                            'defense_pass_defenses'    => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:507',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:2033',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'            => 10,
                            'receiving_targeted'      => 29,
                            'receiving_receptions'    => 18,
                            'receiving_yards'         => 186,
                            'receiving_longest_yards' => 40,
                        ],
                        'post-season-stats' => [
                            'games_played'            => 2,
                            'receiving_targeted'      => 3,
                            'receiving_receptions'    => 3,
                            'receiving_yards'         => 47,
                            'receiving_longest_yards' => 28,
                            'fumbles'                 => 1,
                            'fumbles_lost'            => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:34874',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:5725',
                    '2009-2010'
                ),
                new Statistics(
                    '/sport/football/player:23257',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 7,
                            'defense_solo_tackles'               => 4,
                            'defense_special_teams_solo_tackles' => 2,
                            'defense_fumble_recoveries'          => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 1,
                            'defense_special_teams_solo_tackles' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:11720',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                       => 11,
                            'games_started'                      => 1,
                            'defense_interceptions'              => 1,
                            'defense_interception_yards'         => 18,
                            'defense_solo_tackles'               => 19,
                            'defense_assisted_tackles'           => 2,
                            'defense_special_teams_solo_tackles' => 3,
                            'defense_forced_fumbles'             => 1,
                            'defense_pass_defenses'              => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:30',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 15,
                            'games_started'             => 15,
                            'rushing_plays'             => 21,
                            'rushing_net_yards'         => 10,
                            'rushing_longest_yards'     => 10,
                            'passing_plays_attempted'   => 513,
                            'passing_plays_completed'   => 339,
                            'passing_gross_yards'       => 3925,
                            'passing_net_yards'         => 3753,
                            'passing_longest_yards'     => 45,
                            'passing_plays_intercepted' => 14,
                            'passing_plays_sacked'      => 24,
                            'passing_sacked_yards'      => 172,
                            'fumbles'                   => 11,
                            'fumbles_lost'              => 6,
                            'passing_touchdowns'        => 26,
                            'total_touchdowns'          => 26,
                            'defense_misc_solo_tackles' => 1,
                            'starter_games_won'         => 10,
                            'starter_games_lost'        => 5,
                            'passer_rating'             => 93.157079999999993,
                        ],
                        'post-season-stats' => [
                            'games_played'              => 2,
                            'games_started'             => 2,
                            'rushing_plays'             => 1,
                            'passing_plays_attempted'   => 59,
                            'passing_plays_completed'   => 46,
                            'passing_gross_yards'       => 596,
                            'passing_net_yards'         => 584,
                            'passing_longest_yards'     => 33,
                            'passing_plays_intercepted' => 1,
                            'passing_plays_sacked'      => 2,
                            'passing_sacked_yards'      => 12,
                            'fumbles'                   => 2,
                            'passing_touchdowns'        => 5,
                            'total_touchdowns'          => 5,
                            'defense_fumble_recoveries' => 1,
                            'starter_games_won'         => 1,
                            'starter_games_lost'        => 1,
                            'passer_rating'             => 129.09603999999999,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:8947',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'             => 16,
                            'defense_solo_tackles'     => 22,
                            'defense_assisted_tackles' => 6,
                        ],
                        'post-season-stats' => [
                            'games_played'         => 2,
                            'defense_solo_tackles' => 4,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:28446',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'              => 16,
                            'games_started'             => 1,
                            'rushing_plays'             => 176,
                            'rushing_net_yards'         => 793,
                            'rushing_longest_yards'     => 33,
                            'receiving_targeted'        => 16,
                            'receiving_receptions'      => 12,
                            'receiving_yards'           => 143,
                            'receiving_longest_yards'   => 25,
                            'fumbles'                   => 4,
                            'fumbles_lost'              => 2,
                            'rushing_touchdowns'        => 7,
                            'total_touchdowns'          => 7,
                            'defense_fumble_recoveries' => 1,
                        ],
                        'post-season-stats' => [
                            'games_played'          => 2,
                            'rushing_plays'         => 19,
                            'rushing_net_yards'     => 98,
                            'rushing_longest_yards' => 42,
                            'rushing_touchdowns'    => 1,
                            'total_touchdowns'      => 1,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:306',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'  => 15,
                            'games_started' => 15,
                        ],
                        'post-season-stats' => [
                            'games_played'  => 2,
                            'games_started' => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:274',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'               => 16,
                            'games_started'              => 16,
                            'defense_interceptions'      => 5,
                            'defense_interception_yards' => 56,
                            'defense_solo_tackles'       => 62,
                            'defense_assisted_tackles'   => 13,
                            'defense_sacks'              => 2,
                            'defense_sack_yards'         => 5.5,
                            'defense_forced_fumbles'     => 1,
                            'defense_fumble_recoveries'  => 2,
                            'defense_pass_defenses'      => 13,
                        ],
                        'post-season-stats' => [
                            'games_played'             => 2,
                            'games_started'            => 2,
                            'defense_solo_tackles'     => 10,
                            'defense_assisted_tackles' => 2,
                            'defense_pass_defenses'    => 2,
                        ],
                    ]
                ),
                new Statistics(
                    '/sport/football/player:19009',
                    '2009-2010',
                    [
                        'regular-season-stats' => [
                            'games_played'                           => 16,
                            'rushing_plays'                          => 3,
                            'rushing_net_yards'                      => 17,
                            'rushing_longest_yards'                  => 8,
                            'receiving_targeted'                     => 11,
                            'receiving_receptions'                   => 9,
                            'receiving_yards'                        => 53,
                            'receiving_longest_yards'                => 10,
                            'receiving_touchdowns'                   => 2,
                            'total_touchdowns'                       => 2,
                            'kickoff_returns'                        => 2,
                            'kickoff_return_yards'                   => 27,
                            'kickoff_return_longest_yards'           => 18,
                            'defense_special_teams_solo_tackles'     => 8,
                            'defense_special_teams_assisted_tackles' => 2,
                        ],
                        'post-season-stats' => [
                            'games_played'                       => 2,
                            'rushing_plays'                      => 1,
                            'rushing_net_yards'                  => 3,
                            'rushing_longest_yards'              => 3,
                            'defense_special_teams_solo_tackles' => 1,
                        ],
                    ]
                ),
            ],
            $result
        );
    }
}
