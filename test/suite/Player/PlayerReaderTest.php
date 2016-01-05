<?php

namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class PlayerReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->modifiedTime = DateTime::fromUnixTime(43200);

        $this->request = new PlayerRequest(Sport::MLB(), '2009', 2970);

        $this->response = new PlayerResponse(
            Sport::MLB(),
            new Season(
                '/sport/baseball/season:850',
                '2009',
                Date::fromIsoString('2009-02-24'),
                Date::fromIsoString('2009-11-05')
            ),
            new TeamRef('/sport/baseball/team:2970', 'NY Yankees')
        );

        $this->response->setModifiedTime($this->modifiedTime);

        $this->response->add(new Player('/sport/baseball/player:43566', 'Alfredo',   'Aceves'),       new PlayerSeasonDetails('91', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43263', 'Jonathan',  'Albaladejo'),   new PlayerSeasonDetails('63', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41689', 'Angel',     'Berroa'),       new PlayerSeasonDetails('14', '3B', 'Third Base',        false));
        $this->response->add(new Player('/sport/baseball/player:43452', 'Andrew',    'Brackman'),     new PlayerSeasonDetails('64', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41998', 'Brian',     'Bruney'),       new PlayerSeasonDetails('38', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:40900', 'A.J.',      'Burnett'),      new PlayerSeasonDetails('34', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:42503', 'Melky',     'Cabrera'),      new PlayerSeasonDetails('53', 'RF', 'Right Outfield',    true));
        $this->response->add(new Player('/sport/baseball/player:42272', 'Robinson',  'Cano'),         new PlayerSeasonDetails('24', '2B', 'Second Base',       true));
        $this->response->add(new Player('/sport/baseball/player:41567', 'Kevin',     'Cash'),         new PlayerSeasonDetails('17', 'C',  'Catcher',           false));
        $this->response->add(new Player('/sport/baseball/player:43456', 'Francisco', 'Cervelli'),     new PlayerSeasonDetails('75', 'C',  'Catcher',           true));
        $this->response->add(new Player('/sport/baseball/player:43216', 'Joba',      'Chamberlain'),  new PlayerSeasonDetails('62', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:43726', 'Anthony',   'Claggett'),     new PlayerSeasonDetails('67', 'RP', 'Reliever',          false));
        $this->response->add(new Player('/sport/baseball/player:43569', 'Phil',      'Coke'),         new PlayerSeasonDetails('48', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41557', 'Johnny',    'Damon'),        new PlayerSeasonDetails('18', 'LF', 'Left Outfield',     true));
        $this->response->add(new Player('/sport/baseball/player:43727', 'Wilkin',    'De La Rosa'),   new PlayerSeasonDetails('68', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43198', 'Shelley',   'Duncan'),       new PlayerSeasonDetails('28', 'RF', 'Right Outfield',    true));
        $this->response->add(new Player('/sport/baseball/player:43625', 'Michael',   'Dunn'),         new PlayerSeasonDetails('85', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43764', 'Christian', 'Garcia'),       new PlayerSeasonDetails('70', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43513', 'Brett',     'Gardner'),      new PlayerSeasonDetails('11', 'CF', 'Center Outfield',   true));
        $this->response->add(new Player('/sport/baseball/player:41467', 'Chad',      'Gaudin'),       new PlayerSeasonDetails('49', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:42170', 'Freddy',    'Guzman'),       new PlayerSeasonDetails(null, 'LF', 'Left Outfield',     true));
        $this->response->add(new Player('/sport/baseball/player:43626', 'Eric',      'Hacker'),       new PlayerSeasonDetails('71', 'RP', 'Reliever',          false));
        $this->response->add(new Player('/sport/baseball/player:41536', 'Jerry',     'Hairston Jr.'), new PlayerSeasonDetails(null, '2B', 'Second Base',       true));
        $this->response->add(new Player('/sport/baseball/player:41572', 'Eric',      'Hinske'),       new PlayerSeasonDetails(null, 'RF', 'Right Outfield',    true));
        $this->response->add(new Player('/sport/baseball/player:42710', 'Jason',     'Hirsh'),        new PlayerSeasonDetails('48', 'RP', 'Reliever',          false));
        $this->response->add(new Player('/sport/baseball/player:42760', 'Philip',    'Hughes'),       new PlayerSeasonDetails('65', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:43112', 'Steven',    'Jackson'),      new PlayerSeasonDetails('80', 'SP', 'Starter',           false));
        $this->response->add(new Player('/sport/baseball/player:41511', 'Derek',     'Jeter'),        new PlayerSeasonDetails('2',  'SS', 'Shortstop',         true));
        $this->response->add(new Player('/sport/baseball/player:43241', 'Ian',       'Kennedy'),      new PlayerSeasonDetails('31', 'SP', 'Starter',           false));
        $this->response->add(new Player('/sport/baseball/player:40839', 'Damaso',    'Marte'),        new PlayerSeasonDetails('43', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41517', 'Hideki',    'Matsui'),       new PlayerSeasonDetails('55', 'DH', 'Designated Hitter', true));
        $this->response->add(new Player('/sport/baseball/player:43786', 'Mark',      'Melancon'),     new PlayerSeasonDetails('39', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43454', 'Juan',      'Miranda'),      new PlayerSeasonDetails('72', '1B', 'First Base',        true));
        $this->response->add(new Player('/sport/baseball/player:41463', 'Sergio',    'Mitre'),        new PlayerSeasonDetails('61', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:41581', 'Jose',      'Molina'),       new PlayerSeasonDetails('26', 'C',  'Catcher',           true));
        $this->response->add(new Player('/sport/baseball/player:42057', 'Xavier',    'Nady'),         new PlayerSeasonDetails('22', 'RF', 'Right Outfield',    true));
        $this->response->add(new Player('/sport/baseball/player:43771', 'Ramiro',    'Pena'),         new PlayerSeasonDetails('19', '3B', 'Third Base',        true));
        $this->response->add(new Player('/sport/baseball/player:40721', 'Andy',      'Pettitte'),     new PlayerSeasonDetails('46', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:41507', 'Jorge',     'Posada'),       new PlayerSeasonDetails('20', 'C',  'Catcher',           true));
        $this->response->add(new Player('/sport/baseball/player:43188', 'Edwar',     'Ramirez'),      new PlayerSeasonDetails('36', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41799', 'Cody',      'Ransom'),       new PlayerSeasonDetails('12', '3B', 'Third Base',        true));
        $this->response->add(new Player('/sport/baseball/player:40518', 'Mariano',   'Rivera'),       new PlayerSeasonDetails('42', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:43512', 'David',     'Robertson'),    new PlayerSeasonDetails('30', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:41513', 'Alex',      'Rodriguez'),    new PlayerSeasonDetails('13', '3B', 'Third Base',        true));
        $this->response->add(new Player('/sport/baseball/player:41140', 'CC',        'Sabathia'),     new PlayerSeasonDetails('52', 'SP', 'Starter',           true));
        $this->response->add(new Player('/sport/baseball/player:42683', 'Humberto',  'Sanchez'),      new PlayerSeasonDetails('77', 'RP', 'Reliever',          false));
        $this->response->add(new Player('/sport/baseball/player:42205', 'Nick',      'Swisher'),      new PlayerSeasonDetails('33', 'RF', 'Right Outfield',    true));
        $this->response->add(new Player('/sport/baseball/player:41634', 'Mark',      'Teixeira'),     new PlayerSeasonDetails('25', '1B', 'First Base',        true));
        $this->response->add(new Player('/sport/baseball/player:40397', 'Brett',     'Tomko'),        new PlayerSeasonDetails('28', 'RP', 'Reliever',          false));
        $this->response->add(new Player('/sport/baseball/player:41163', 'Josh',      'Towers'),       new PlayerSeasonDetails('12', 'RP', 'Reliever',          true));
        $this->response->add(new Player('/sport/baseball/player:42886', 'Jose',      'Veras'),        new PlayerSeasonDetails('41', 'RP', 'Reliever',          false));

        // Player #42341 was altered in the XML to only include a single name, which
        // does occur occassionally. The XML omits the first-name, but the Siphon
        // objects instead omit the last name, which is arguably more accurate.
        $this->response->add(new Player('/sport/baseball/player:42341', 'Wang',      null),           new PlayerSeasonDetails('40', 'SP', 'Starter',           true));

        $this->reader = new PlayerReader($this->urlBuilder(), $this->xmlReader()->mock());

        $this->resolve = Phony::spy();
        $this->reject = Phony::spy();
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/players.xml', $this->modifiedTime);
        $this->reader->read($this->request)->done($this->resolve, $this->reject);

        $this->xmlReader->read->calledWith(
            'http://sdi.example/sport/v2/baseball/MLB/players/2009/players_2970_MLB.xml?apiKey=xxx'
        );
        $this->reject->never()->called();
        $response = $this->resolve->calledWith($this->isInstanceOf(PlayerResponse::class))->argument();

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
