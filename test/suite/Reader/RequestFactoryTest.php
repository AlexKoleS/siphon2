<?php
namespace Icecave\Siphon\Reader;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleType;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamRequest;
use PHPUnit_Framework_TestCase;

class RequestFactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->factory = new RequestFactory;
    }

    /**
     * @dataProvider urlTestVectors
     */
    public function testCreate($url, $request)
    {
        $this->assertEquals(
            $request,
            $this->factory->create($url)
        );
    }

    public function testCreateWithUnsupportedUrl()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported URL.'
        );

        $this->factory->create('<nope>');
    }

    public function urlTestVectors()
    {
        return [
            'atom' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z')
                ),
            ],
            'atom with feed' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&feed=/foo',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    '/foo'
                ),
            ],
            'atom with limit' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&limit=100',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    100
                ),
            ],
            'atom with asc order' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&order=asc',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    5000,
                    SORT_ASC
                ),
            ],
            'atom with desc order' => [
                '/Atom?newerThan=2010-01-02T03:04:05Z&order=desc',
                new AtomRequest(
                    DateTime::fromIsoString('2010-01-02T03:04:05Z'),
                    null,
                    5000,
                    SORT_DESC
                ),
            ],

            'schedule' => [
                '/sport/v2/baseball/MLB/schedule/schedule_MLB.xml',
                new ScheduleRequest(
                    Sport::MLB(),
                    ScheduleType::FULL()
                ),
            ],

            'schedule with limit' => [
                '/sport/v2/baseball/MLB/schedule/schedule_MLB_2_days.xml',
                new ScheduleRequest(
                    Sport::MLB(),
                    ScheduleType::LIMIT_2_DAYS()
                ),
            ],

            'schedule with deleted competitions' => [
                '/sport/v2/baseball/MLB/games-deleted/games_deleted_MLB.xml',
                new ScheduleRequest(
                    Sport::MLB(),
                    ScheduleType::DELETED()
                ),
            ],

            'team' => [
                '/sport/v2/baseball/MLB/teams/2015/teams_MLB.xml',
                new TeamRequest(
                    Sport::MLB(),
                    '2015'
                ),
            ],

            'player' => [
                '/sport/v2/baseball/MLB/players/2015/players_12345_MLB.xml',
                new PlayerRequest(
                    Sport::MLB(),
                    '2015',
                    12345
                ),
            ],

            'player statistics (combined)' => [
                '/sport/v2/baseball/MLB/player-stats/2015/player_stats_12345_MLB.xml',
                new PlayerStatisticsRequest(
                    Sport::MLB(),
                    '2015',
                    12345,
                    StatisticsType::COMBINED()
                ),
            ],

            'player statistics (split)' => [
                '/sport/v2/baseball/MLB/player-split-stats/2015/player_split_stats_12345_MLB.xml',
                new PlayerStatisticsRequest(
                    Sport::MLB(),
                    '2015',
                    12345,
                    StatisticsType::SPLIT()
                ),
            ],

            'player images' => [
                '/sport/v2/baseball/MLB/player-images/2015/player-images_12345_MLB.xml',
                new ImageRequest(
                    Sport::MLB(),
                    '2015',
                    12345
                ),
            ],

            'player injuries' => [
                '/sport/v2/baseball/MLB/injuries/injuries_MLB.xml',
                new InjuryRequest(
                    Sport::MLB()
                ),
            ],

            'live scores' => [
                '/sport/v2/baseball/MLB/livescores/livescores_12345.xml',
                new LiveScoreRequest(
                    Sport::MLB(),
                    12345
                ),
            ],

            'box scores' => [
                '/sport/v2/baseball/MLB/boxscores/2015/boxscore_MLB_12345.xml',
                new BoxScoreRequest(
                    Sport::MLB(),
                    '2015',
                    12345
                ),
            ],
        ];
    }
}
