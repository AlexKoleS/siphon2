<?php
namespace Icecave\Siphon;

use GuzzleHttp\Client as HttpClient;
use Icecave\Siphon\Atom\AtomReader;
use Icecave\Siphon\Atom\AtomReaderInterface;
use Icecave\Siphon\Atom\AtomRequest;
use Icecave\Siphon\Player\PlayerReader;
use Icecave\Siphon\Player\PlayerReaderInterface;
use Icecave\Siphon\Player\PlayerRequest;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Reader\UrlBuilder;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReader;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\ScheduleReader;
use Icecave\Siphon\Schedule\ScheduleReaderInterface;
use Icecave\Siphon\Schedule\ScheduleRequest;
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
            new PlayerReader($xmlReader)
        );
    }

    public function __construct(
        UrlBuilderInterface $urlBuilder,
        XmlReaderInterface $xmlReader,
        AtomReaderInterface $atomReader,
        ScheduleReaderInterface $scheduleReader,
        TeamReaderInterface $teamReader,
        PlayerReaderInterface $playerReader
    ) {
        $this->urlBuilder     = $urlBuilder;
        $this->xmlReader      = $xmlReader;
        $this->atomReader     = $atomReader;
        $this->scheduleReader = $scheduleReader;
        $this->teamReader     = $teamReader;
        $this->playerReader   = $playerReader;
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

    private $urlBuilder;
    private $xmlReader;
    private $atomReader;
    private $scheduleReader;
    private $teamReader;
    private $playerReader;
}
