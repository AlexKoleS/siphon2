<?php

namespace Icecave\Siphon\Team;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class TeamReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new TeamRequest(
            Sport::MLB(),
            '2009'
        );

        $this->response = new TeamResponse(
            Sport::MLB(),
            new Season(
                '/sport/baseball/season:850',
                '2009',
                Date::fromIsoString('2009-02-24'),
                Date::fromIsoString('2009-11-05')
            )
        );

        $this->response->add(new Team('/sport/baseball/team:2976', 'Milwaukee',     'MIL',   'Brewers'));
        $this->response->add(new Team('/sport/baseball/team:2982', 'Chicago',       'CHC',   'Cubs'));
        $this->response->add(new Team('/sport/baseball/team:2971', 'Pittsburgh',    'PIT',   'Pirates'));
        $this->response->add(new Team('/sport/baseball/team:2961', 'Cincinnati',    'CIN',   'Reds'));
        $this->response->add(new Team('/sport/baseball/team:2981', 'Houston',       'HOU',   'Astros'));
        $this->response->add(new Team('/sport/baseball/team:2975', 'St. Louis',     'STL',   'Cardinals'));
        $this->response->add(new Team('/sport/baseball/team:2968', 'Arizona',       'ARI',   'Diamondbacks'));
        $this->response->add(new Team('/sport/baseball/team:2967', 'Los Angeles',   'LA',    'Dodgers'));
        $this->response->add(new Team('/sport/baseball/team:2962', 'San Francisco', 'SF',    'Giants'));
        $this->response->add(new Team('/sport/baseball/team:2956', 'Colorado',      'COL',   'Rockies'));
        $this->response->add(new Team('/sport/baseball/team:2955', 'San Diego',     'SD',    'Padres'));
        $this->response->add(new Team('/sport/baseball/team:2958', 'Philadelphia',  'PHI',   'Phillies'));
        $this->response->add(new Team('/sport/baseball/team:2964', 'New York',      'NYM',   'Mets'));
        $this->response->add(new Team('/sport/baseball/team:2957', 'Atlanta',       'ATL',   'Braves'));
        $this->response->add(new Team('/sport/baseball/team:2972', 'Washington',    'WAS',   'Nationals'));
        $this->response->add(new Team('/sport/baseball/team:2963', 'Florida',       'FLA',   'Marlins'));
        $this->response->add(new Team('/sport/baseball/team:2983', 'Minnesota',     'MIN',   'Twins'));
        $this->response->add(new Team('/sport/baseball/team:2974', 'Chicago',       'CHW',   'White Sox'));
        $this->response->add(new Team('/sport/baseball/team:2980', 'Cleveland',     'CLE',   'Indians'));
        $this->response->add(new Team('/sport/baseball/team:2965', 'Kansas City',   'KC',    'Royals'));
        $this->response->add(new Team('/sport/baseball/team:2978', 'Detroit',       'DET',   'Tigers'));
        $this->response->add(new Team('/sport/baseball/team:2979', 'Los Angeles',   'LAA',   'Angels'));
        $this->response->add(new Team('/sport/baseball/team:2969', 'Oakland',       'OAK',   'Athletics'));
        $this->response->add(new Team('/sport/baseball/team:2973', 'Seattle',       'SEA',   'Mariners'));
        $this->response->add(new Team('/sport/baseball/team:2977', 'Texas',         'TEX',   'Rangers'));
        $this->response->add(new Team('/sport/baseball/team:2966', 'Boston',        'BOS',   'Red Sox'));
        $this->response->add(new Team('/sport/baseball/team:2970', 'New York',      'NYY',   'Yankees'));
        $this->response->add(new Team('/sport/baseball/team:2959', 'Baltimore',     'BAL',   'Orioles'));
        $this->response->add(new Team('/sport/baseball/team:2984', 'Toronto',       'TOR',   'Blue Jays'));
        $this->response->add(new Team('/sport/baseball/team:2960', 'Tampa Bay',     'TB',    'Rays'));

        $this->reader = new TeamReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Team/teams.xml');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/teams/2009/teams_MLB.xml');

        $this->assertEquals(
            $this->response,
            $response
        );
    }

    public function testReadWithoutNickName()
    {
        $this->setUpXmlReader('Team/teams-without-nickname.xml');

        $this->response->clear();
        $this->response->add(new Team('/sport/basketball/team:665163', 'North Dakota', 'UND'));
        $this->response->setSeason(
            new Season(
                '/sport/basketball/season:664550',
                '2014-2015',
                Date::fromIsoString('2014-11-01'),
                Date::fromIsoString('2015-04-30')
            )
        );

        $response = $this
            ->reader
            ->read($this->request);

        $this->assertEquals(
            $this->response,
            $response
        );
    }

    public function testReadIncludesTeamsOutsideConference()
    {
        $this->setUpXmlReader('Team/teams-outside-conference.xml');

        $this->response->clear();
        $this->response->add(new Team('/sport/basketball/team:2369', 'Southern Utah',     'SUU',   'Thunderbirds'));
        $this->response->add(new Team('/sport/basketball/team:2471', 'St. Mary\'s (MD)',  'STMMD', 'Seahawks'));
        $this->response->setSeason(
            new Season(
                '/sport/basketball/season:664550',
                '2014-2015',
                Date::fromIsoString('2014-11-01'),
                Date::fromIsoString('2015-04-30')
            )
        );

        $response = $this
            ->reader
            ->read($this->request);

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
