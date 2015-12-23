<?php

namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsGroup;
use Icecave\Siphon\Team\Team;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class BoxScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new BoxScoreReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Score/boxscores.xml');

        $homeTeam = new TeamRef('/sport/baseball/team:2958', 'Philadelphia');
        $awayTeam = new TeamRef('/sport/baseball/team:2967', 'LA Dodgers');

        $expected = new BoxScoreResponse(
            new Competition(
                '/sport/baseball/competition:291828',
                CompetitionStatus::COMPLETE(),
                DateTime::fromIsoString('2009-10-21T20:05:00-04:00'),
                Sport::MLB(),
                new Season(
                    '/sport/baseball/season:850',
                    '2009',
                    Date::fromIsoString('2009-02-24'),
                    Date::fromIsoString('2009-11-05')
                ),
                $homeTeam,
                $awayTeam
            ),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'results',
                        [],
                        [
                            'hits' => 8,
                            'runs' => 10,
                        ]
                    ),
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'          => 1,
                            'games_won'             => 1,
                            'at_bats'               => 31,
                            'hits'                  => 8,
                            'runs'                  => 10,
                            'runs_inning_1'         => 3,
                            'runs_inning_2'         => 1,
                            'runs_inning_4'         => 2,
                            'runs_inning_6'         => 2,
                            'runs_inning_7'         => 1,
                            'runs_inning_8'         => 1,
                            'home_runs'             => 4,
                            'doubles'               => 2,
                            'runs_batted_in'        => 9,
                            'walks'                 => 4,
                            'strikeouts'            => 10,
                            'sacrifice_hits'        => 1,
                            'left_on_base'          => 8,
                            'team_left_on_base'     => 5,
                            'two_out_left_on_base'  => 3,
                            'hit_by_pitch'          => 3,
                            'rlisp_two_out'         => 3,
                            'outs_pitched'          => 27,
                            'earned_runs_against'   => 4,
                            'earned_run_average'    => 4.0,
                            'total_bases'           => 22,
                            'slugging_percentage'   => 0.7096774,
                            'batting_average'       => 0.2580645,
                            'on_base_percentage'    => 0.39473686,
                            'on_base_plus_slugging' => 1.1044142544269562,
                        ]
                    ),
                ]
            ),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'results',
                        [],
                        [
                            'hits' => 8,
                            'runs' => 4,
                        ]
                    ),
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'          => 1,
                            'games_lost'            => 1,
                            'at_bats'               => 35,
                            'hits'                  => 8,
                            'runs'                  => 4,
                            'runs_inning_1'         => 1,
                            'runs_inning_2'         => 1,
                            'runs_inning_5'         => 1,
                            'runs_inning_8'         => 1,
                            'home_runs'             => 3,
                            'doubles'               => 1,
                            'runs_batted_in'        => 4,
                            'walks'                 => 3,
                            'strikeouts'            => 7,
                            'left_on_base'          => 15,
                            'team_left_on_base'     => 7,
                            'two_out_left_on_base'  => 3,
                            'rlisp_two_out'         => 3,
                            'outs_pitched'          => 24,
                            'wild_pitches'          => 1,
                            'hit_batters'           => 3,
                            'earned_runs_against'   => 10,
                            'earned_run_average'    => 11.25,
                            'total_bases'           => 18,
                            'slugging_percentage'   => 0.51428574,
                            'batting_average'       => 0.22857143,
                            'on_base_percentage'    => 0.28947368,
                            'on_base_plus_slugging' => 0.8037594258785248,
                        ]
                    ),
                ]
            )
        );
        $expected->setUrl('http://sdi.example.org/sport/v2/baseball/MLB/boxscores/2009/boxscore_MLB_291828.xml');

        $expected->add(
            $homeTeam,
            new Player('/sport/baseball/player:42863', 'Cole', 'Hamels'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'            => 1,
                            'games_started'           => 1,
                            'at_bats'                 => 1,
                            'strikeouts'              => 1,
                            'sacrifice_hits'          => 1,
                            'pitcher_games_played'    => 1,
                            'no_decisions'            => 1,
                            'pitcher_games_started'   => 1,
                            'outs_pitched'            => 13,
                            'earned_run_average'      => 6.2307689999999996,
                            'whip'                    => 1.3846153999999999,
                            'pitcher_hits'            => 5,
                            'pitcher_runs'            => 3,
                            'pitcher_earned_runs'     => 3,
                            'pitcher_walks'           => 1,
                            'pitcher_strikeouts'      => 3,
                            'pitcher_home_runs'       => 3,
                            'pitches_thrown'          => 94,
                            'starting_pitches_thrown' => 94,
                            'strikes_thrown'          => 62,
                            'ground_ball_outs'        => 5,
                            'fly_ball_outs'           => 5,
                            'batters_faced'           => 19,
                        ]
                    ),
                ]
            )
        );

        $expected->add(
            $homeTeam,
            new Player('/sport/baseball/player:43078', 'J.A.', 'Happ'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'         => 1,
                            'pitcher_games_played' => 1,
                            'outs_pitched'         => 1,
                            'whip'                 => 3.0,
                            'pitcher_walks'        => 1,
                            'pitches_thrown'       => 7,
                            'strikes_thrown'       => 3,
                            'fly_ball_outs'        => 1,
                            'batters_faced'        => 2,
                            'inherited_runners'    => 1,
                        ]
                    ),
                ]
            )
        );

        $expected->add(
            $awayTeam,
            new Player('/sport/baseball/player:40838', 'Vicente', 'Padilla'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'            => 1,
                            'games_started'           => 1,
                            'at_bats'                 => 1,
                            'pitcher_games_played'    => 1,
                            'losses'                  => 1,
                            'pitcher_games_started'   => 1,
                            'outs_pitched'            => 9,
                            'earned_run_average'      => 18.0,
                            'whip'                    => 2.0,
                            'pitcher_hits'            => 4,
                            'pitcher_runs'            => 6,
                            'pitcher_earned_runs'     => 6,
                            'pitcher_walks'           => 2,
                            'pitcher_strikeouts'      => 3,
                            'pitcher_home_runs'       => 2,
                            'pitches_thrown'          => 55,
                            'starting_pitches_thrown' => 55,
                            'strikes_thrown'          => 34,
                            'ground_ball_outs'        => 4,
                            'fly_ball_outs'           => 2,
                            'batters_faced'           => 15,
                        ]
                    ),
                ]
            )
        );

        $expected->add(
            $awayTeam,
            new Player('/sport/baseball/player:43419', 'Ramon', 'Troncoso'),
            new StatisticsCollection(
                [
                    new StatisticsGroup(
                        'game-stats',
                        [],
                        [
                            'games_played'           => 1,
                            'pitcher_games_played'   => 1,
                            'outs_pitched'           => 2,
                            'whip'                   => 1.5,
                            'pitcher_walks'          => 1,
                            'pitcher_hit_by_pitch'   => 1,
                            'pitcher_sacrifice_hits' => 1,
                            'pitches_thrown'         => 19,
                            'strikes_thrown'         => 8,
                            'ground_ball_outs'       => 2,
                            'batters_faced'          => 4,
                            'inherited_runners'      => 1,
                        ]
                    ),
                ]
            )
        );

        $request = new BoxScoreRequest(Sport::MLB(), '2009', 291828);
        $this->reader->read($request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith('/sport/v2/baseball/MLB/boxscores/2009/boxscore_MLB_291828.xml');
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(BoxScoreResponse::class))->argument();
        $this->assertEquals($expected, $response);
    }

    public function testReadFinalized()
    {
        $this->setUpXmlReader('Score/boxscores-finalized.xml');
        $request = new BoxScoreRequest(Sport::MLB(), '2009', 291828);
        $this->reader->read($request)->done($this->resolve, $this->reject);

        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(BoxScoreResponse::class))->argument();
        $this->assertTrue($response->isFinalized());
    }

    public function testReadWithUnsupportedRequest()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unsupported request.');

        $this->reader->read(Phony::mock(RequestInterface::class)->mock())->done();
    }

    public function testIsSupported()
    {
        $request = new BoxScoreRequest(Sport::MLB(), '2009', 291828);

        $this->assertTrue($this->reader->isSupported($request));
        $this->assertFalse($this->reader->isSupported(Phony::mock(RequestInterface::class)->mock()));
    }
}
