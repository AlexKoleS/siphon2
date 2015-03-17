<?php
namespace Icecave\Siphon;

use GuzzleHttp\Client as HttpClient;

/**
 * The global factory used to create Siphon feed readers.
 *
 * @api
 */
class Factory implements FactoryInterface
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
     * Create an atom reader.
     *
     * @return AtomReaderInterface
     */
    public function createAtomReader()
    {
        return new Atom\AtomReader(
            $this->xmlReader
        );
    }

    /**
     * Create a schedule reader.
     *
     * @return Schedule\ScheduleReaderInterface
     */
    public function createScheduleReader()
    {
        return new Schedule\ScheduleReader(
            $this->xmlReader
        );
    }

    private $urlBuilder;
    private $xmlReader;
}
