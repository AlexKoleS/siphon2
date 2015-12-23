<?php

namespace Icecave\Siphon\Team\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
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

class TeamStatisticsReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new TeamStatisticsRequest(Sport::NFL(), '2014-2015');

        $this->response = new TeamStatisticsResponse(
            Sport::NFL(),
            new Season(
                '/sport/football/season:96',
                '2014-2015',
                Date::fromIsoString('2014-08-01'),
                Date::fromIsoString('2015-02-09')
            ),
            StatisticsType::COMBINED()
        );

        $this->reader = new TeamStatisticsReader($this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Team/team-stats.xml');

        $this->response->add(
            new TeamRef('/sport/football/team:10', 'Tennessee'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'pre-season-stats',
                        [
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_won'  => 2,
                            'games_lost' => 2,
                        ]
                    ),
                    new StatisticsGroup(
                        'pre-season-home-stats',
                        [
                            'home-away'         => 'home',
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_won'  => 1,
                            'games_lost' => 1,
                        ]
                    ),
                    new StatisticsGroup(
                        'pre-season-away-stats',
                        [
                            'home-away'         => 'away',
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_won'  => 1,
                            'games_lost' => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:13', 'Indianapolis'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'pre-season-stats',
                        [
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_lost' => 4,
                        ]
                    ),
                    new StatisticsGroup(
                        'pre-season-home-stats',
                        [
                            'home-away'         => 'home',
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_lost' => 2,
                        ]
                    ),
                    new StatisticsGroup(
                        'pre-season-away-stats',
                        [
                            'home-away'         => 'away',
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_lost' => 2,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:3', 'Kansas City'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'pre-season-stats',
                        [
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_won'  => 1,
                            'games_lost' => 3,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:27', 'Washington'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'pre-season-stats',
                        [
                            'season-phase-from' => 'Pre-Season Hall-of-Fame Week',
                            'season-phase-to'   => 'Pre-Season Week 4',
                        ],
                        [
                            'games_won'  => 3,
                            'games_lost' => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:967', 'Team Irvin'),
            new StatisticsCollection([])
        );

        $this->response->add(
            new TeamRef('/sport/football/team:968', 'Team Carter'),
            new StatisticsCollection([])
        );

        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith('/sport/v2/football/NFL/team-stats/2014-2015/team_stats_NFL.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(TeamStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadSplitStats()
    {
        $this->setUpXmlReader('Team/team-split-stats.xml');

        $this->request->setType(StatisticsType::SPLIT());
        $this->response->setType(StatisticsType::SPLIT());

        $this->response->add(
            new TeamRef('/sport/football/team:10', 'Tennessee'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'regular-season-away-stats',
                        [
                            'home-away'         => 'away',
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'  => 8,
                            'games_won'     => 1,
                            'games_lost'    => 7,
                        ]
                    ),
                    new StatisticsGroup(
                        'regular-season-vs-opponent-stats',
                        [
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                            'opp-team-id'       => '/sport/football/team:24',
                            'season-id'         => '/sport/football/season:96',
                        ],
                        [
                            'games_played'  => 1,
                            'games_lost'    => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:13', 'Indianapolis'),
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
                            'games_played'  => 8,
                            'games_won'     => 6,
                            'games_lost'    => 2,
                        ]
                    ),
                    new StatisticsGroup(
                        'regular-season-away-stats',
                        [
                            'home-away'         => 'away',
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'  => 8,
                            'games_won'     => 5,
                            'games_lost'    => 3,
                        ]
                    ),
                    new StatisticsGroup(
                        'post-season-home-stats',
                        [
                            'home-away'         => 'home',
                            'season-phase-from' => 'Wildcard',
                            'season-phase-to'   => 'Super Bowl',
                        ],
                        [
                            'games_played'  => 1,
                            'games_won'     => 1,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:3', 'Kansas City'),
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
                            'games_played'  => 8,
                            'games_won'     => 6,
                            'games_lost'    => 2,
                        ]
                    ),
                    new StatisticsGroup(
                        'regular-season-away-stats',
                        [
                            'home-away'         => 'away',
                            'season-phase-from' => 'Week 1',
                            'season-phase-to'   => 'Week 18',
                        ],
                        [
                            'games_played'  => 8,
                            'games_won'     => 3,
                            'games_lost'    => 5,
                        ]
                    ),
                ]
            )
        );

        $this->response->add(
            new TeamRef('/sport/football/team:27', 'Washington'),
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
                            'games_played'  => 8,
                            'games_won'     => 3,
                            'games_lost'    => 5,
                        ]
                    ),
                ]
            )
        );

        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read
            ->calledWith('/sport/v2/football/NFL/team-split-stats/2014-2015/team_split_stats_NFL.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(TeamStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadEmpty()
    {
        $this->setUpXmlReader('Team/empty.xml');

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
