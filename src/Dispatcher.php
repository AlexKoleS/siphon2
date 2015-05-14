<?php
namespace Icecave\Siphon;

use GuzzleHttp\Client as HttpClient;
use Icecave\Siphon\Atom\AtomReader;
use Icecave\Siphon\Reader\CompositeReader;
use Icecave\Siphon\Reader\UrlBuilder;
use Icecave\Siphon\Reader\XmlReader;

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

        return new static($urlBuilder, $xmlReader);
    }

    /**
     * @param UrlBuilderInterface $urlBuilder The URL builder to use.
     * @param XmlReaderInterface  $xmlReader  The XML reader to use.
     */
    public function __construct(UrlBuilderInterface $urlBuilder, XmlReaderInterface $xmlReader)
    {
        $this->urlBuilder = $urlBuilder;
        $this->xmlReader  = $xmlReader;
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
        if ($this->atomReader) {
            $this->atomReader = new AtomReader($this->xmlReader);
        }

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
        if ($this->scheduleReader) {
            $this->scheduleReader = new ScheduleReader($this->xmlReader);
        }

        return $this->scheduleReader->read($request);
    }

    private $urlBuilder;
    private $xmlReader;
    private $atomReader;
    private $scheduleReader;
}
