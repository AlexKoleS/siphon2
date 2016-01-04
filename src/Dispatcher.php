<?php

namespace Icecave\Siphon;

use Clue\React\Buzz\Browser;
use Icecave\Siphon\Atom\AtomReader;
use Icecave\Siphon\Atom\AtomReaderInterface;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\Image\ImageReader;
use Icecave\Siphon\Player\Image\ImageReaderInterface;
use Icecave\Siphon\Player\Image\ImageRequest;
use Icecave\Siphon\Player\Injury\InjuryReader;
use Icecave\Siphon\Player\Injury\InjuryReaderInterface;
use Icecave\Siphon\Player\Injury\InjuryRequest;
use Icecave\Siphon\Player\PlayerReader;
use Icecave\Siphon\Player\PlayerReaderInterface;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsReader;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsReaderInterface;
use Icecave\Siphon\Player\Statistics\PlayerStatisticsRequest;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilder;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\UrlBuilder;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReader;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Result\ResultReader;
use Icecave\Siphon\Result\ResultReaderInterface;
use Icecave\Siphon\Result\ResultRequest;
use Icecave\Siphon\Schedule\ScheduleReader;
use Icecave\Siphon\Schedule\ScheduleReaderInterface;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Score\BoxScore\BoxScoreReader;
use Icecave\Siphon\Score\BoxScore\BoxScoreReaderInterface;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreReader;
use Icecave\Siphon\Score\LiveScore\LiveScoreReaderInterface;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Team\Statistics\TeamStatisticsReader;
use Icecave\Siphon\Team\Statistics\TeamStatisticsReaderInterface;
use Icecave\Siphon\Team\Statistics\TeamStatisticsRequest;
use Icecave\Siphon\Team\TeamReader;
use Icecave\Siphon\Team\TeamReaderInterface;
use Icecave\Siphon\Team\TeamRequest;
use React\EventLoop\LoopInterface;

/**
 * The dispatcher is a facade for easily servicing any Siphon request.
 */
class Dispatcher implements DispatcherInterface, RequestVisitorInterface
{
    /**
     * Create a factory that uses the given API key.
     *
     * @param LoopInterface $loop   The event loop.
     * @param string        $apiKey The API key used to authenticate.
     *
     * @return FactoryInterface
     */
    public static function create(LoopInterface $loop, $apiKey)
    {
        $urlBuilder = new UrlBuilder($apiKey);
        $requestUrlBuilder = new RequestUrlBuilder($urlBuilder);
        $httpClient = new Browser($loop);
        $xmlReader = new XmlReader($httpClient);

        return new static(
            $urlBuilder,
            $xmlReader,
            new AtomReader($requestUrlBuilder, $xmlReader),
            new ScheduleReader($requestUrlBuilder, $xmlReader),
            new ResultReader($requestUrlBuilder, $xmlReader),
            new TeamReader($requestUrlBuilder, $xmlReader),
            new TeamStatisticsReader($requestUrlBuilder, $xmlReader),
            new PlayerReader($requestUrlBuilder, $xmlReader),
            new PlayerStatisticsReader($requestUrlBuilder, $xmlReader),
            new ImageReader($requestUrlBuilder, $xmlReader),
            new InjuryReader($requestUrlBuilder, $xmlReader),
            new LiveScoreReader($requestUrlBuilder, $xmlReader),
            new BoxScoreReader($requestUrlBuilder, $xmlReader)
        );
    }

    public function __construct(
        UrlBuilderInterface $urlBuilder,
        XmlReaderInterface $xmlReader,
        AtomReaderInterface $atomReader,
        ScheduleReaderInterface $scheduleReader,
        ResultReaderInterface $resultReader,
        TeamReaderInterface $teamReader,
        TeamStatisticsReaderInterface $teamStatisticsReader,
        PlayerReaderInterface $playerReader,
        PlayerStatisticsReaderInterface $playerStatisticsReader,
        ImageReaderInterface $imageReader,
        InjuryReaderInterface $injuryReader,
        LiveScoreReaderInterface $liveScoreReader,
        BoxScoreReaderInterface $boxScoreReader
    ) {
        $this->urlBuilder             = $urlBuilder;
        $this->xmlReader              = $xmlReader;
        $this->atomReader             = $atomReader;
        $this->scheduleReader         = $scheduleReader;
        $this->resultReader           = $resultReader;
        $this->teamReader             = $teamReader;
        $this->teamStatisticsReader   = $teamStatisticsReader;
        $this->playerReader           = $playerReader;
        $this->playerStatisticsReader = $playerStatisticsReader;
        $this->imageReader            = $imageReader;
        $this->injuryReader           = $injuryReader;
        $this->liveScoreReader        = $liveScoreReader;
        $this->boxScoreReader         = $boxScoreReader;
    }

    /**
     * Get the URL builder used by the factory.
     *
     * @return UrlBuilderInterface
     */
    public function urlBuilder()
    {
        return $this->urlBuilder;
    }

    /**
     * Get the XML reader used by the factory.
     *
     * @return XmlReaderInterface
     */
    public function xmlReader()
    {
        return $this->xmlReader;
    }

    /**
     * Make a request and return the response.
     *
     * @param RequestInterface The request.
     *
     * @return ResponseInterface        [via promise] The response.
     * @throws InvalidArgumentException [via promise] If the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        $this->operation = __FUNCTION__;

        return $request->accept($this);
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        $this->operation = __FUNCTION__;

        return $request->accept($this);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param AtomRequest $request
     *
     * @return mixed
     */
    public function visitAtomRequest(AtomRequest $request)
    {
        return $this->atomReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param ScheduleRequest $request
     *
     * @return mixed
     */
    public function visitScheduleRequest(ScheduleRequest $request)
    {
        return $this->scheduleReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param ResultRequest $request
     *
     * @return mixed
     */
    public function visitResultRequest(ResultRequest $request)
    {
        return $this->resultReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param TeamRequest $request
     *
     * @return mixed
     */
    public function visitTeamRequest(TeamRequest $request)
    {
        return $this->teamReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param TeamStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitTeamStatisticsRequest(TeamStatisticsRequest $request)
    {
        return $this->teamStatisticsReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param PlayerRequest $request
     *
     * @return mixed
     */
    public function visitPlayerRequest(PlayerRequest $request)
    {
        return $this->playerReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param PlayerStatisticsRequest $request
     *
     * @return mixed
     */
    public function visitPlayerStatisticsRequest(PlayerStatisticsRequest $request)
    {
        return $this->playerStatisticsReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @access private
     *
     * @param ImageRequest $request
     *
     * @return mixed
     */
    public function visitImageRequest(ImageRequest $request)
    {
        return $this->imageReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @param InjuryRequest $request
     *
     * @return mixed
     */
    public function visitInjuryRequest(InjuryRequest $request)
    {
        return $this->injuryReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @param LiveScoreRequest $request
     *
     * @return mixed
     */
    public function visitLiveScoreRequest(LiveScoreRequest $request)
    {
        return $this->liveScoreReader->{$this->operation}($request);
    }

    /**
     * Visit the given request.
     *
     * @param BoxScoreRequest $request
     *
     * @return mixed
     */
    public function visitBoxScoreRequest(BoxScoreRequest $request)
    {
        return $this->boxScoreReader->{$this->operation}($request);
    }

    private $urlBuilder;
    private $xmlReader;
    private $atomReader;
    private $scheduleReader;
    private $resultReader;
    private $teamReader;
    private $playerReader;
    private $imageReader;
    private $injuryReader;
    private $liveScoreReader;
    private $boxScoreReader;
    private $operation;
}
