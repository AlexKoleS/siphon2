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
use Icecave\Siphon\Result\ResultReaderInterface;
use Icecave\Siphon\Result\ResultRequest;
use Icecave\Siphon\Result\ResultResponse;
use Icecave\Siphon\Schedule\ScheduleReaderInterface;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Schedule\ScheduleResponse;
use Icecave\Siphon\Score\BoxScore\BoxScoreReaderInterface;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\BoxScore\BoxScoreResponse;
use Icecave\Siphon\Score\LiveScore\LiveScoreReaderInterface;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreResponse;
use Icecave\Siphon\Team\Statistics\TeamStatisticsReaderInterface;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\Statistics\TeamStatisticsResponse;
use Icecave\Siphon\Team\TeamReaderInterface;
use Icecave\Siphon\Team\TeamRequest;
use Icecave\Siphon\Team\TeamResponse;
use PHPUnit_Framework_TestCase;
use React\EventLoop\LoopInterface;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlBuilder = Phony::mock(UrlBuilderInterface::class);
        $this->xmlReader = Phony::mock(XmlReaderInterface::class);

        $this->atomReader = Phony::mock(AtomReaderInterface::class);
        $this->atomReader->isSupported->returns(true);

        $this->scheduleReader = Phony::mock(ScheduleReaderInterface::class);
        $this->scheduleReader->isSupported->returns(true);

        $this->resultReader = Phony::mock(ResultReaderInterface::class);
        $this->resultReader->isSupported->returns(true);

        $this->teamReader = Phony::mock(TeamReaderInterface::class);
        $this->teamReader->isSupported->returns(true);

        $this->teamStatsReader = Phony::mock(TeamStatisticsReaderInterface::class);
        $this->teamStatsReader->isSupported->returns(true);

        $this->playerReader = Phony::mock(PlayerReaderInterface::class);
        $this->playerReader->isSupported->returns(true);

        $this->playerStatsReader = Phony::mock(PlayerStatisticsReaderInterface::class);
        $this->playerStatsReader->isSupported->returns(true);

        $this->imageReader = Phony::mock(ImageReaderInterface::class);
        $this->imageReader->isSupported->returns(true);

        $this->injuryReader = Phony::mock(InjuryReaderInterface::class);
        $this->injuryReader->isSupported->returns(true);

        $this->liveScoreReader = Phony::mock(LiveScoreReaderInterface::class);
        $this->liveScoreReader->isSupported->returns(true);

        $this->boxScoreReader = Phony::mock(BoxScoreReaderInterface::class);
        $this->boxScoreReader->isSupported->returns(true);

        $this->subject = new Dispatcher(
            $this->urlBuilder->mock(),
            $this->xmlReader->mock(),
            $this->atomReader->mock(),
            $this->scheduleReader->mock(),
            $this->resultReader->mock(),
            $this->teamReader->mock(),
            $this->teamStatsReader->mock(),
            $this->playerReader->mock(),
            $this->playerStatsReader->mock(),
            $this->imageReader->mock(),
            $this->injuryReader->mock(),
            $this->liveScoreReader->mock(),
            $this->boxScoreReader->mock()
        );
    }

    public function testCreate()
    {
        $loop = Phony::mock(LoopInterface::class)->mock();
        $this->subject = Dispatcher::create($loop, '<api key>');

        $this->assertInstanceOf(
            DispatcherInterface::class,
            $this->subject
        );

        $this->assertContains(
            '%3Capi+key%3E',
            $this->subject->urlBuilder()->build('/')
        );
    }

    public function testUrlBuilder()
    {
        $this->assertSame(
            $this->urlBuilder->mock(),
            $this->subject->urlBuilder()
        );
    }

    public function testXmlReader()
    {
        $this->assertSame(
            $this->xmlReader->mock(),
            $this->subject->xmlReader()
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

    public function testResultRequest()
    {
        $this->dispatchTest(
            ResultRequest::class,
            ResultResponse::class,
            $this->resultReader
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

    public function testTeamStatisticsRequest()
    {
        $this->dispatchTest(
            TeamStatisticsRequest::class,
            TeamStatisticsResponse::class,
            $this->teamStatsReader
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

    public function testLiveScoreRequest()
    {
        $this->dispatchTest(
            LiveScoreRequest::class,
            LiveScoreResponse::class,
            $this->liveScoreReader
        );
    }

    public function testBoxScoreRequest()
    {
        $this->dispatchTest(
            BoxScoreRequest::class,
            BoxScoreResponse::class,
            $this->boxScoreReader
        );
    }

    private function dispatchTest(
        $requestClass,
        $responseClass,
        $reader
    ) {
        $request  = Phony::mock($requestClass);
        $response = Phony::mock($responseClass);

        $request
            ->accept
            ->forwards();

        $reader
            ->read
            ->returns($response->mock());

        $result = $this
            ->subject
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
            $this->subject->isSupported(
                $request->mock()
            )
        );

        $reader
            ->isSupported
            ->calledWith(
                $this->identicalTo($request->mock())
            );
    }
}
