<?php
namespace Icecave\Siphon;

use GuzzleHttp\Client as HttpClient;
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
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\UrlBuilder;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReader;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\ScheduleReader;
use Icecave\Siphon\Schedule\ScheduleReaderInterface;
use Icecave\Siphon\Schedule\ScheduleRequest;
use Icecave\Siphon\Score\BoxScore\BoxScoreReader;
use Icecave\Siphon\Score\BoxScore\BoxScoreReaderInterface;
use Icecave\Siphon\Score\BoxScore\BoxScoreRequest;
use Icecave\Siphon\Score\LiveScore\LiveScoreReader;
use Icecave\Siphon\Score\LiveScore\LiveScoreReaderInterface;
use Icecave\Siphon\Score\LiveScore\LiveScoreRequest;
use Icecave\Siphon\Team\TeamReader;
use Icecave\Siphon\Team\TeamReaderInterface;
use Icecave\Siphon\Team\TeamRequest;

/**
 * The dispatcher is a facade for easily servicing any Siphon request.
 */
class Dispatcher implements DispatcherInterface, RequestVisitorInterface
{
    /**
     * Create a factory that uses the given API key.
     *
     * @param string $apiKey The API key used to authenticate.
     *
     * @return FactoryInterface
     */
    public static function create($apiKey)
    {
        $urlBuilder = new UrlBuilder($apiKey);
        $httpClient = new HttpClient;
        $xmlReader  = new XmlReader($urlBuilder, $httpClient);

        return new static(
            $urlBuilder,
            $xmlReader,
            new AtomReader($xmlReader),
            new ScheduleReader($xmlReader),
            new TeamReader($xmlReader),
            new PlayerReader($xmlReader),
            new PlayerStatisticsReader($xmlReader),
            new ImageReader($xmlReader),
            new InjuryReader($xmlReader),
            new LiveScoreReader($xmlReader),
            new BoxScoreReader($xmlReader)
        );
    }

    public function __construct(
        UrlBuilderInterface $urlBuilder,
        XmlReaderInterface $xmlReader,
        AtomReaderInterface $atomReader,
        ScheduleReaderInterface $scheduleReader,
        TeamReaderInterface $teamReader,
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
        $this->teamReader             = $teamReader;
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
     * @return ResponseInterface        The response.
     * @throws InvalidArgumentException if the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        return $request->accept($this);
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        return true;
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
        return $this->atomReader->read($request);
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
        return $this->scheduleReader->read($request);
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
        return $this->teamReader->read($request);
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
        return $this->playerReader->read($request);
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
        return $this->playerStatisticsReader->read($request);
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
        return $this->imageReader->read($request);
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
        return $this->injuryReader->read($request);
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
        return $this->liveScoreReader->read($request);
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
        return $this->boxScoreReader->read($request);
    }

    private $urlBuilder;
    private $xmlReader;
    private $atomReader;
    private $scheduleReader;
    private $teamReader;
    private $playerReader;
    private $imageReader;
    private $injuryReader;
    private $liveScoreReader;
    private $boxScoreReader;
}
