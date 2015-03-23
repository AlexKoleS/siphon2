<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\XmlReaderTestTrait;
use PHPUnit_Framework_TestCase;

class PlayerReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->reader = new PlayerReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/players.xml');

        $result = $this->reader->read(
            'baseball',
            'MLB',
            '2009',
            '/sport/baseball/team:12345'
        );

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/baseball/MLB/players/2009/players_12345_MLB.xml');

        $this->assertEquals(
            [
                [
                    new Player('/sport/baseball/player:43566', 'Alfredo', 'Aceves'),
                    new SeasonDetails('/sport/baseball/player:43566', '2009', '91', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43263', 'Jonathan', 'Albaladejo'),
                    new SeasonDetails('/sport/baseball/player:43263', '2009', '63', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41689', 'Angel', 'Berroa'),
                    new SeasonDetails('/sport/baseball/player:41689', '2009', '14', '3B', 'Third Base', false),
                ],
                [
                    new Player('/sport/baseball/player:43452', 'Andrew', 'Brackman'),
                    new SeasonDetails('/sport/baseball/player:43452', '2009', '64', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41998', 'Brian', 'Bruney'),
                    new SeasonDetails('/sport/baseball/player:41998', '2009', '38', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:40900', 'A.J.', 'Burnett'),
                    new SeasonDetails('/sport/baseball/player:40900', '2009', '34', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:42503', 'Melky', 'Cabrera'),
                    new SeasonDetails('/sport/baseball/player:42503', '2009', '53', 'RF', 'Right Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:42272', 'Robinson', 'Cano'),
                    new SeasonDetails('/sport/baseball/player:42272', '2009', '24', '2B', 'Second Base', true),
                ],
                [
                    new Player('/sport/baseball/player:41567', 'Kevin', 'Cash'),
                    new SeasonDetails('/sport/baseball/player:41567', '2009', '17', 'C', 'Catcher', false),
                ],
                [
                    new Player('/sport/baseball/player:43456', 'Francisco', 'Cervelli'),
                    new SeasonDetails('/sport/baseball/player:43456', '2009', '75', 'C', 'Catcher', true),
                ],
                [
                    new Player('/sport/baseball/player:43216', 'Joba', 'Chamberlain'),
                    new SeasonDetails('/sport/baseball/player:43216', '2009', '62', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:43726', 'Anthony', 'Claggett'),
                    new SeasonDetails('/sport/baseball/player:43726', '2009', '67', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:43569', 'Phil', 'Coke'),
                    new SeasonDetails('/sport/baseball/player:43569', '2009', '48', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41557', 'Johnny', 'Damon'),
                    new SeasonDetails('/sport/baseball/player:41557', '2009', '18', 'LF', 'Left Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:43727', 'Wilkin', 'De La Rosa'),
                    new SeasonDetails('/sport/baseball/player:43727', '2009', '68', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43198', 'Shelley', 'Duncan'),
                    new SeasonDetails('/sport/baseball/player:43198', '2009', '28', 'RF', 'Right Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:43625', 'Michael', 'Dunn'),
                    new SeasonDetails('/sport/baseball/player:43625', '2009', '85', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43764', 'Christian', 'Garcia'),
                    new SeasonDetails('/sport/baseball/player:43764', '2009', '70', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43513', 'Brett', 'Gardner'),
                    new SeasonDetails('/sport/baseball/player:43513', '2009', '11', 'CF', 'Center Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:41467', 'Chad', 'Gaudin'),
                    new SeasonDetails('/sport/baseball/player:41467', '2009', '49', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:42170', 'Freddy', 'Guzman'),
                    new SeasonDetails('/sport/baseball/player:42170', '2009', null, 'LF', 'Left Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:43626', 'Eric', 'Hacker'),
                    new SeasonDetails('/sport/baseball/player:43626', '2009', '71', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:41536', 'Jerry', 'Hairston Jr.'),
                    new SeasonDetails('/sport/baseball/player:41536', '2009', null, '2B', 'Second Base', true),
                ],
                [
                    new Player('/sport/baseball/player:41572', 'Eric', 'Hinske'),
                    new SeasonDetails('/sport/baseball/player:41572', '2009', null, 'RF', 'Right Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:42710', 'Jason', 'Hirsh'),
                    new SeasonDetails('/sport/baseball/player:42710', '2009', '48', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:42760', 'Philip', 'Hughes'),
                    new SeasonDetails('/sport/baseball/player:42760', '2009', '65', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:43112', 'Steven', 'Jackson'),
                    new SeasonDetails('/sport/baseball/player:43112', '2009', '80', 'SP', 'Starter', false),
                ],
                [
                    new Player('/sport/baseball/player:41511', 'Derek', 'Jeter'),
                    new SeasonDetails('/sport/baseball/player:41511', '2009', '2', 'SS', 'Shortstop', true),
                ],
                [
                    new Player('/sport/baseball/player:43241', 'Ian', 'Kennedy'),
                    new SeasonDetails('/sport/baseball/player:43241', '2009', '31', 'SP', 'Starter', false),
                ],
                [
                    new Player('/sport/baseball/player:40839', 'Damaso', 'Marte'),
                    new SeasonDetails('/sport/baseball/player:40839', '2009', '43', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41517', 'Hideki', 'Matsui'),
                    new SeasonDetails('/sport/baseball/player:41517', '2009', '55', 'DH', 'Designated Hitter', true),
                ],
                [
                    new Player('/sport/baseball/player:43786', 'Mark', 'Melancon'),
                    new SeasonDetails('/sport/baseball/player:43786', '2009', '39', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43454', 'Juan', 'Miranda'),
                    new SeasonDetails('/sport/baseball/player:43454', '2009', '72', '1B', 'First Base', true),
                ],
                [
                    new Player('/sport/baseball/player:41463', 'Sergio', 'Mitre'),
                    new SeasonDetails('/sport/baseball/player:41463', '2009', '61', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:41581', 'Jose', 'Molina'),
                    new SeasonDetails('/sport/baseball/player:41581', '2009', '26', 'C', 'Catcher', true),
                ],
                [
                    new Player('/sport/baseball/player:42057', 'Xavier', 'Nady'),
                    new SeasonDetails('/sport/baseball/player:42057', '2009', '22', 'RF', 'Right Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:43771', 'Ramiro', 'Pena'),
                    new SeasonDetails('/sport/baseball/player:43771', '2009', '19', '3B', 'Third Base', true),
                ],
                [
                    new Player('/sport/baseball/player:40721', 'Andy', 'Pettitte'),
                    new SeasonDetails('/sport/baseball/player:40721', '2009', '46', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:41507', 'Jorge', 'Posada'),
                    new SeasonDetails('/sport/baseball/player:41507', '2009', '20', 'C', 'Catcher', true),
                ],
                [
                    new Player('/sport/baseball/player:43188', 'Edwar', 'Ramirez'),
                    new SeasonDetails('/sport/baseball/player:43188', '2009', '36', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41799', 'Cody', 'Ransom'),
                    new SeasonDetails('/sport/baseball/player:41799', '2009', '12', '3B', 'Third Base', true),
                ],
                [
                    new Player('/sport/baseball/player:40518', 'Mariano', 'Rivera'),
                    new SeasonDetails('/sport/baseball/player:40518', '2009', '42', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:43512', 'David', 'Robertson'),
                    new SeasonDetails('/sport/baseball/player:43512', '2009', '30', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:41513', 'Alex', 'Rodriguez'),
                    new SeasonDetails('/sport/baseball/player:41513', '2009', '13', '3B', 'Third Base', true),
                ],
                [
                    new Player('/sport/baseball/player:41140', 'CC', 'Sabathia'),
                    new SeasonDetails('/sport/baseball/player:41140', '2009', '52', 'SP', 'Starter', true),
                ],
                [
                    new Player('/sport/baseball/player:42683', 'Humberto', 'Sanchez'),
                    new SeasonDetails('/sport/baseball/player:42683', '2009', '77', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:42205', 'Nick', 'Swisher'),
                    new SeasonDetails('/sport/baseball/player:42205', '2009', '33', 'RF', 'Right Outfield', true),
                ],
                [
                    new Player('/sport/baseball/player:41634', 'Mark', 'Teixeira'),
                    new SeasonDetails('/sport/baseball/player:41634', '2009', '25', '1B', 'First Base', true),
                ],
                [
                    new Player('/sport/baseball/player:40397', 'Brett', 'Tomko'),
                    new SeasonDetails('/sport/baseball/player:40397', '2009', '28', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:41163', 'Josh', 'Towers'),
                    new SeasonDetails('/sport/baseball/player:41163', '2009', '12', 'RP', 'Reliever', true),
                ],
                [
                    new Player('/sport/baseball/player:42886', 'Jose', 'Veras'),
                    new SeasonDetails('/sport/baseball/player:42886', '2009', '41', 'RP', 'Reliever', false),
                ],
                [
                    new Player('/sport/baseball/player:42341', 'Chien-Ming', 'Wang'),
                    new SeasonDetails('/sport/baseball/player:42341', '2009', '40', 'SP', 'Starter', true),
                ],
            ],
            $result
        );
    }
}
