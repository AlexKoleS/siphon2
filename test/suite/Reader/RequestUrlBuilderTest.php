<?php

namespace Icecave\Siphon\Reader;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Hockey\ProbableGoalies\HockeyProbableGoaliesRequest;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Result\ResultRequest;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleType;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\TeamRequest;
use PHPUnit_Framework_TestCase;

class RequestUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->base = 'http://sdi.example';
        $this->urlBuilder = new UrlBuilder('xxx', $this->base);
        $this->subject = new RequestUrlBuilder($this->urlBuilder);

        $this->apiBase = $this->base . '/sport/v2';
    }

    public function testBuildWithAtomRequest()
    {
        $updatedTime = DateTime::fromUnixTime(86400);

        $this->assertSame(
            $this->base . '/Atom?apiKey=xxx&newerThan=1970-01-02T00%3A00%3A00%2B00%3A00&maxCount=5000&order=asc',
            $this->subject->build(new AtomRequest($updatedTime))
        );
        $this->assertSame(
            $this->base .
                '/Atom?apiKey=xxx&newerThan=1970-01-02T00%3A00%3A00%2B00%3A00&maxCount=5000&order=asc&feed=%2Ffoo',
            $this->subject->build(new AtomRequest($updatedTime, '/foo'))
        );
        $this->assertSame(
            $this->base . '/Atom?apiKey=xxx&newerThan=1970-01-02T00%3A00%3A00%2B00%3A00&maxCount=100&order=asc',
            $this->subject->build(new AtomRequest($updatedTime, null, 100))
        );
        $this->assertSame(
            $this->base . '/Atom?apiKey=xxx&newerThan=1970-01-02T00%3A00%3A00%2B00%3A00&maxCount=100&order=desc',
            $this->subject->build(new AtomRequest($updatedTime, null, 100, SORT_DESC))
        );
    }

    public function testBuildWithScheduleRequest()
    {
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/schedule/schedule_MLB.xml?apiKey=xxx',
            $this->subject->build(new ScheduleRequest(Sport::MLB()))
        );
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/schedule/schedule_MLB_2_days.xml?apiKey=xxx',
            $this->subject->build(new ScheduleRequest(Sport::MLB(), ScheduleType::LIMIT_2_DAYS()))
        );
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/games-deleted/games_deleted_MLB.xml?apiKey=xxx',
            $this->subject->build(new ScheduleRequest(Sport::MLB(), ScheduleType::DELETED()))
        );
    }

    public function testBuildWithResultRequest()
    {
        $this->assertSame(
            $this->apiBase . '/hockey/NHL/results/2015-2016/results_NHL.xml?apiKey=xxx',
            $this->subject->build(new ResultRequest(Sport::NHL(), '2015-2016'))
        );
    }

    public function testBuildWithTeamRequest()
    {
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/teams/2009/teams_MLB.xml?apiKey=xxx',
            $this->subject->build(new TeamRequest(Sport::MLB(), '2009'))
        );
    }

    public function testBuildWithTeamStatisticsRequest()
    {
        $this->assertSame(
            $this->apiBase . '/football/NFL/team-stats/2014-2015/team_stats_NFL.xml?apiKey=xxx',
            $this->subject->build(new TeamStatisticsRequest(Sport::NFL(), '2014-2015'))
        );
        $this->assertSame(
            $this->apiBase . '/football/NFL/team-split-stats/2014-2015/team_split_stats_NFL.xml?apiKey=xxx',
            $this->subject->build(new TeamStatisticsRequest(Sport::NFL(), '2014-2015', StatisticsType::SPLIT()))
        );
    }

    public function testBuildWithPlayerRequest()
    {
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/players/2009/players_2970_MLB.xml?apiKey=xxx',
            $this->subject->build(new PlayerRequest(Sport::MLB(), '2009', 2970))
        );
    }

    public function testBuildWithPlayerStatisticsRequest()
    {
        $this->assertSame(
            $this->apiBase . '/football/NFL/player-stats/2014-2015/player_stats_23_NFL.xml?apiKey=xxx',
            $this->subject->build(new PlayerStatisticsRequest(Sport::NFL(), '2014-2015', 23))
        );
        $this->assertSame(
            $this->apiBase . '/football/NFL/player-split-stats/2014-2015/player_split_stats_23_NFL.xml?apiKey=xxx',
            $this->subject->build(new PlayerStatisticsRequest(Sport::NFL(), '2014-2015', 23, StatisticsType::SPLIT()))
        );
    }

    public function testBuildWithImageRequest()
    {
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/player-images/2015/player-images_2955_MLB.xml?apiKey=xxx',
            $this->subject->build(new ImageRequest(Sport::MLB(), '2015', 2955))
        );
    }

    public function testBuildWithInjuryRequest()
    {
        $this->assertSame(
            $this->apiBase . '/football/NFL/injuries/injuries_NFL.xml?apiKey=xxx',
            $this->subject->build(new InjuryRequest(Sport::NFL()))
        );
    }

    public function testBuildWithLiveScoreRequest()
    {
        $this->assertSame(
            $this->apiBase . '/hockey/NHL/livescores/livescores_23816.xml?apiKey=xxx',
            $this->subject->build(new LiveScoreRequest(Sport::NHL(), 23816))
        );
    }

    public function testBuildWithBoxScoreRequest()
    {
        $this->assertSame(
            $this->apiBase . '/baseball/MLB/boxscores/2009/boxscore_MLB_291828.xml?apiKey=xxx',
            $this->subject->build(new BoxScoreRequest(Sport::MLB(), '2009', 291828))
        );
    }

    public function testBuildWithHockeyProbableGoaliesRequest()
    {
        $this->assertSame(
            $this->apiBase . '/hockey/NHL/probable-goalies/probable_goalies_NHL.xml?apiKey=xxx',
            $this->subject->build(new HockeyProbableGoaliesRequest(Sport::NHL()))
        );
    }
}
