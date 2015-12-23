<?php

namespace Icecave\Siphon\Player\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsGroup;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class PlayerStatisticsReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new PlayerStatisticsRequest(Sport::NFL(), '2014-2015', 23);
        $this->response = new PlayerStatisticsResponse(
            Sport::NFL(),
            new Season(
                '/sport/football/season:96',
                '2014-2015',
                Date::fromIsoString('2014-08-01'),
                Date::fromIsoString('2015-02-09')
            ),
            new TeamRef('/sport/football/team:23', 'Arizona'),
            StatisticsType::COMBINED()
        );

        $this->reader = new PlayerStatisticsReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/stats.xml');

        $this->response
            ->setUrl('http://sdi.example.org/sport/v2/football/NFL/player-stats/2014-2015/player_stats_23_NFL.xml');

        $this->response->add(
            new Player('/sport/football/player:338', 'John', 'Abraham'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'  => 1,
                            'games_started' => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new Player('/sport/football/player:35195', 'Sam', 'Acho'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'                           => 16,
                            'games_started'                          => 3,
                            'interception_returned_longest_yards'    => 3,
                            'defense_interceptions'                  => 1,
                            'defense_interception_yards'             => 3,
                            'defense_solo_tackles'                   => 21,
                            'defense_assisted_tackles'               => 7,
                            'defense_special_teams_solo_tackles'     => 2,
                            'defense_special_teams_assisted_tackles' => 1,
                            'defense_sacks'                          => 1.0,
                            'defense_sack_yards'                     => 15.0,
                            'defense_tackles_for_loss'               => 4.0,
                            'defense_pass_defenses'                  => 3,
                        ]
                    ),
                    new StatisticsGroup(
                        'post-season-stats',
                        [
                            'season-phase-from' => 'Wildcard',
                            'season-phase-to'   => 'Super Bowl',
                        ],
                        [
                            'games_played'             => 1,
                            'games_started'            => 1,
                            'defense_solo_tackles'     => 2,
                            'defense_assisted_tackles' => 5,
                            'defense_sacks'            => 1.0,
                            'defense_forced_fumbles'   => 1,
                        ]
                    ),
                ]
            )
        );

        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith('/sport/v2/football/NFL/player-stats/2014-2015/player_stats_23_NFL.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(PlayerStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadSplitStats()
    {
        $this->setUpXmlReader('Player/split-stats.xml');

        $this->response->setUrl(
            'http://sdi.example.org/sport/v2/football/NFL/player-split-stats/2014-2015/player_split_stats_23_NFL.xml'
        );

        $this->request->setType(StatisticsType::SPLIT());
        $this->response->setType(StatisticsType::SPLIT());

        $this->response->add(
            new Player('/sport/football/player:338', 'John', 'Abraham'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-home-stats',
                        [
                            'home-away'         => 'home',
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'  => 1,
                            'games_started' => 1,
                        ]
                    ),
                    new StatisticsGroup(
                        'regular-season-vs-opponent-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                            'opp-team-id'       => '/sport/football/team:11',
                            'season-id'         => '/sport/football/season:96',
                        ],
                        [
                            'games_played'  => 1,
                            'games_started' => 1,
                        ]
                    ),
                ]
            )
        );

        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read
            ->calledWith('/sport/v2/football/NFL/player-split-stats/2014-2015/player_split_stats_23_NFL.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(PlayerStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadEmpty()
    {
        $this->setUpXmlReader('Player/empty.xml');

        $this->setExpectedException(NotFoundException::class);
        $this->reader->read($this->request)->done();
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->reader->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    public function testIsSupported()
    {
        $this->assertTrue($this->reader->isSupported($this->request));
        $this->assertFalse($this->reader->isSupported(Phony::mock(RequestInterface::class)->mock()));
    }
}
