<?php
namespace Icecave\Siphon\Score\BoxScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsCollection;
use Icecave\Siphon\Statistics\StatisticsGroup;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class BoxScoreReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new BoxScoreReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Score/boxscores.xml');

        $response = $this
            ->reader
            ->read(
                new BoxScoreRequest(
                    Sport::MLB(),
                    '2009',
                    291828
                )
            );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/boxscores/2009/boxscore_MLB_291828.xml');

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
                new TeamRef('/sport/baseball/team:2958', 'Philadelphia'),
                new TeamRef('/sport/baseball/team:2967', 'LA Dodgers')
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

        $this->assertEquals(
            $expected,
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
            $this->reader->isSupported(
                new BoxScoreRequest(
                    Sport::MLB(),
                    '<season>',
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
