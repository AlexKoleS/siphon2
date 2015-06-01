<?php
namespace Icecave\Siphon\Player;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\Date;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderTestTrait;
use Icecave\Siphon\Schedule\Season;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamRef;
use PHPUnit_Framework_TestCase;

class PlayerStatisticsReaderTest extends PHPUnit_Framework_TestCase
{
    use XmlReaderTestTrait;

    public function setUp()
    {
        $this->request = new PlayerStatisticsRequest(
            Sport::NFL(),
            '2009-2010',
            23
        );

        $this->response = new PlayerStatisticsResponse(
            Sport::MLB(),
            new Season(
                '/sport/football/season:74',
                '2009-2010',
                Date::fromIsoString('2009-08-01'),
                Date::fromIsoString('2010-02-28')
            ),
            new TeamRef(
                '/sport/football/team:23',
                'Arizona'
            )
        );

        $this->reader = new PlayerStatisticsReader(
            $this->xmlReader()->mock()
        );
    }

    public function testRead()
    {
        $this->setUpXmlReader('Player/stats.xml');

        $response = $this
            ->reader
            ->read($this->request);

        $this
            ->xmlReader
            ->read
            ->calledWith('/sport/v2/football/NFL/player-stats/2009-2010/player_stats_23_NFL.xml');

        $this->assertEquals(
            $this->response,
            $response
        );

        $this->markTestIncomplete();
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
