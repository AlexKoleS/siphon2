<?php
namespace Icecave\Siphon;

use Eloquent\Phony\Phpunit\Phony;
use GuzzleHttp\Client as HttpClient;
use PHPUnit_Framework_TestCase;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->xmlReader  = Phony::mock(XmlReaderInterface::class);

        $this->factory = new Factory(
            $this->urlBuilder->mock(),
            $this->xmlReader->mock()
        );
    }

    public function testCreate()
    {
        $apiKey     = '<API KEY>';
        $urlBuilder = new UrlBuilder($apiKey);
        $httpClient = new HttpClient;
        $xmlReader  = new XmlReader($urlBuilder, $httpClient);

        $this->assertEquals(
            new Factory(
                $urlBuilder,
                $xmlReader
            ),
            Factory::create($apiKey)
        );
    }

    public function testUrlBuilder()
    {
        $this->assertSame(
            $this->urlBuilder->mock(),
            $this->factory->urlBuilder()
        );
    }

    public function testXmlReader()
    {
        $this->assertSame(
            $this->xmlReader->mock(),
            $this->factory->xmlReader()
        );
    }

    public function testCreateAtomReader()
    {
        $this->assertEquals(
            new Atom\AtomReader($this->xmlReader->mock()),
            $this->factory->createAtomReader()
        );
    }

    public function testCreateScheduleReader()
    {
        $this->assertEquals(
            new Schedule\ScheduleReader($this->xmlReader->mock()),
            $this->factory->createScheduleReader()
        );
    }

    public function testCreateTeamReader()
    {
        $this->assertEquals(
            new Team\TeamReader($this->xmlReader->mock()),
            $this->factory->createTeamReader()
        );
    }

    public function testCreateLiveScoreReader()
    {
        $this->assertEquals(
            new Score\LiveScore\LiveScoreReader($this->xmlReader->mock()),
            $this->factory->createLiveScoreReader()
        );
    }
}
