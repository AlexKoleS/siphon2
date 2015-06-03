<?php
namespace Icecave\Siphon;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Atom\AtomReaderInterface;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Atom\AtomResponse;
use Icecave\Siphon\Player\Image\ImageReaderInterface;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Image\ImageResponse;
use Icecave\Siphon\Player\Injury\InjuryReaderInterface;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\Injury\InjuryResponse;
use Icecave\Siphon\Player\PlayerReaderInterface;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\PlayerResponse;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsReaderInterface;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsResponse;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\ScheduleReaderInterface;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleResponse;
use Icecave\Siphon\Team\TeamReaderInterface;
use Icecave\Siphon\Team\TeamRequest;
use Icecave\Siphon\Team\TeamResponse;
use PHPUnit_Framework_TestCase;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder        = Phony::mock(UrlBuilderInterface::class);
        $this->xmlReader         = Phony::mock(XmlReaderInterface::class);
        $this->atomReader        = Phony::mock(AtomReaderInterface::class);
        $this->scheduleReader    = Phony::mock(ScheduleReaderInterface::class);
        $this->teamReader        = Phony::mock(TeamReaderInterface::class);
        $this->playerReader      = Phony::mock(PlayerReaderInterface::class);
        $this->playerStatsReader = Phony::mock(PlayerStatisticsReaderInterface::class);
        $this->imageReader       = Phony::mock(ImageReaderInterface::class);
        $this->injuryReader      = Phony::mock(InjuryReaderInterface::class);

        $this->dispatcher = new Dispatcher(
            $this->urlBuilder->mock(),
            $this->xmlReader->mock(),
            $this->atomReader->mock(),
            $this->scheduleReader->mock(),
            $this->teamReader->mock(),
            $this->playerReader->mock(),
            $this->playerStatsReader->mock(),
            $this->imageReader->mock(),
            $this->injuryReader->mock()
        );
    }

    public function testCreate()
    {
        $this->dispatcher = Dispatcher::create('<api key>');

        $this->assertInstanceOf(
            DispatcherInterface::class,
            $this->dispatcher
        );

        $this->assertContains(
            '%3Capi+key%3E',
            $this->dispatcher->urlBuilder()->build('/')
        );
    }

    public function testUrlBuilder()
    {
        $this->assertSame(
            $this->urlBuilder->mock(),
            $this->dispatcher->urlBuilder()
        );
    }

    public function testXmlReader()
    {
        $this->assertSame(
            $this->xmlReader->mock(),
            $this->dispatcher->xmlReader()
        );
    }

    public function testReadAtomRequest()
    {
        $this->dispatchTest(
            AtomRequest::class,
            AtomResponse::class,
            $this->atomReader
        );
    }

    public function testScheduleRequest()
    {
        $this->dispatchTest(
            ScheduleRequest::class,
            ScheduleResponse::class,
            $this->scheduleReader
        );
    }

    public function testTeamRequest()
    {
        $this->dispatchTest(
            TeamRequest::class,
            TeamResponse::class,
            $this->teamReader
        );
    }

    public function testPlayerRequest()
    {
        $this->dispatchTest(
            PlayerRequest::class,
            PlayerResponse::class,
            $this->playerReader
        );
    }

    public function testPlayerStatisticsRequest()
    {
        $this->dispatchTest(
            PlayerStatisticsRequest::class,
            PlayerStatisticsResponse::class,
            $this->playerStatsReader
        );
    }

    public function testImageRequest()
    {
        $this->dispatchTest(
            ImageRequest::class,
            ImageResponse::class,
            $this->imageReader
        );
    }

    public function testInjuryRequest()
    {
        $this->dispatchTest(
            InjuryRequest::class,
            InjuryResponse::class,
            $this->injuryReader
        );
    }

    private function dispatchTest(
        $requestClass,
        $responseClass,
        $reader
    ) {
        $request  = Phony::fullMock($requestClass);
        $response = Phony::fullMock($responseClass);

        $request
            ->accept
            ->forwards();

        $reader
            ->read
            ->returns($response->mock());

        $result = $this
            ->dispatcher
            ->read(
                $request->mock()
            );

        $reader
            ->read
            ->calledWith(
                $this->identicalTo($request->mock())
            );

        $this->assertSame(
            $response->mock(),
            $result
        );

        $this->assertTrue(
            $this->dispatcher->isSupported(
                $request->mock()
            )
        );
    }
}
