<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class TeamReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->setUpXmlReader('Team/teams.xml');

        $this->reader = new TeamReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $teams = $this->reader->read('baseball', 'MLB', '2009');

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/teams/2009/teams_MLB.xml');

        $this->assertEquals(
            [
                new Team('/sport/baseball/team:2976', 'Milwaukee',     'Brewers',      'MIL'),
                new Team('/sport/baseball/team:2982', 'Chicago',       'Cubs',         'CHC'),
                new Team('/sport/baseball/team:2971', 'Pittsburgh',    'Pirates',      'PIT'),
                new Team('/sport/baseball/team:2961', 'Cincinnati',    'Reds',         'CIN'),
                new Team('/sport/baseball/team:2981', 'Houston',       'Astros',       'HOU'),
                new Team('/sport/baseball/team:2975', 'St. Louis',     'Cardinals',    'STL'),
                new Team('/sport/baseball/team:2968', 'Arizona',       'Diamondbacks', 'ARI'),
                new Team('/sport/baseball/team:2967', 'Los Angeles',   'Dodgers',      'LA'),
                new Team('/sport/baseball/team:2962', 'San Francisco', 'Giants',       'SF'),
                new Team('/sport/baseball/team:2956', 'Colorado',      'Rockies',      'COL'),
                new Team('/sport/baseball/team:2955', 'San Diego',     'Padres',       'SD'),
                new Team('/sport/baseball/team:2958', 'Philadelphia',  'Phillies',     'PHI'),
                new Team('/sport/baseball/team:2964', 'New York',      'Mets',         'NYM'),
                new Team('/sport/baseball/team:2957', 'Atlanta',       'Braves',       'ATL'),
                new Team('/sport/baseball/team:2972', 'Washington',    'Nationals',    'WAS'),
                new Team('/sport/baseball/team:2963', 'Florida',       'Marlins',      'FLA'),
                new Team('/sport/baseball/team:2983', 'Minnesota',     'Twins',        'MIN'),
                new Team('/sport/baseball/team:2974', 'Chicago',       'White Sox',    'CHW'),
                new Team('/sport/baseball/team:2980', 'Cleveland',     'Indians',      'CLE'),
                new Team('/sport/baseball/team:2965', 'Kansas City',   'Royals',       'KC'),
                new Team('/sport/baseball/team:2978', 'Detroit',       'Tigers',       'DET'),
                new Team('/sport/baseball/team:2979', 'Los Angeles',   'Angels',       'LAA'),
                new Team('/sport/baseball/team:2969', 'Oakland',       'Athletics',    'OAK'),
                new Team('/sport/baseball/team:2973', 'Seattle',       'Mariners',     'SEA'),
                new Team('/sport/baseball/team:2977', 'Texas',         'Rangers',      'TEX'),
                new Team('/sport/baseball/team:2966', 'Boston',        'Red Sox',      'BOS'),
                new Team('/sport/baseball/team:2970', 'New York',      'Yankees',      'NYY'),
                new Team('/sport/baseball/team:2959', 'Baltimore',     'Orioles',      'BAL'),
                new Team('/sport/baseball/team:2984', 'Toronto',       'Blue Jays',    'TOR'),
                new Team('/sport/baseball/team:2960', 'Tampa Bay',     'Rays',         'TB'),
            ],
            $teams
        );
    }
}
