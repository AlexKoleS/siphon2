<?php

namespace Icecave\Siphon\Player\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
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
        $this->modifiedTime = DateTime::fromUnixTime(43200);

        $this->request = new PlayerStatisticsRequest(
            Sport::NFL(),
            '2014-2015',
            23
        );

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

        $this->response->setModifiedTime($this->modifiedTime);

        $this->subject = new PlayerStatisticsReader(
            $this->urlBuilder(),
            $this->xmlReader()->mock()
        );

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/stats.xml', $this->modifiedTime);

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

        $this->subject->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/football/NFL/player-stats/2014-2015/player_stats_23_NFL.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(PlayerStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadSplitStats()
    {
        $this->setUpXmlReader('Player/split-stats.xml', $this->modifiedTime);

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

        $this->subject->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example' .
                '/sport/v2/football/NFL/player-split-stats/2014-2015/player_split_stats_23_NFL.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(PlayerStatisticsResponse::class))->argument();

        $this->assertEquals($this->response, $response);
    }

    public function testReadEmpty()
    {
        $this->setUpXmlReader('Player/empty.xml');

        $this->setExpectedException(NotFoundException::class);
        $this->subject->read($this->request)->done();
    }

    public function testReadEmptyNoSeason()
    {
        $this->setUpXmlReader('Player/empty-no-season.xml');

        $this->setExpectedException(NotFoundException::class);
        $this->subject->read($this->request)->done();
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->subject->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    /**
     * @dataProvider isSupportedTestVectors
     */
    public function testIsSupported(Sport $sport, StatisticsType $type, $isSupported)
    {
        $this->request->setSport($sport);
        $this->request->setType($type);

        $this->assertSame(
            $isSupported,
            $this->subject->isSupported($this->request)
        );
    }

    public function isSupportedTestVectors()
    {
        $result = [];

        foreach (Sport::members() as $sport) {
            foreach (StatisticsType::members() as $type) {
                $result[$sport->league() . '-' . $type->value()] = [
                    $sport,
                    $type,
                    true,
                ];
            }
        }

        $result['NCAAF-split'][2] = false;
        $result['NCAAB-split'][2] = false;

        return $result;
    }

    public function testIsSupportedWithUnknownType()
    {
        $this->assertFalse(
            $this->subject->isSupported(Phony::mock(RequestInterface::class)->mock())
        );
    }
}
