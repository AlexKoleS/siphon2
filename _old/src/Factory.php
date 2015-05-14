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
            $this->urlBuilder,
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

    /**
     * Create a team reader.
     *
     * @return Team\TeamReaderInterface
     */
    public function createTeamReader()
    {
        return new Team\TeamReader(
            $this->xmlReader
        );
    }

    /**
     * Create a live score reader.
     *
     * @return Score\LiveScore\LiveScoreReaderInterface
     */
    public function createLiveScoreReader()
    {
        return new Score\LiveScore\LiveScoreReader(
            $this->xmlReader
        );
    }

    /**
     * Create a box score reader.
     *
     * @return Score\BoxScore\BoxScoreReaderInterface
     */
    public function createBoxScoreReader()
    {
        return new Score\BoxScore\BoxScoreReader(
            $this->xmlReader
        );
    }

    /**
     * Create a player reader.
     *
     * @return Player\PlayerReaderInterface
     */
    public function createPlayerReader()
    {
        return new Player\PlayerReader(
            $this->xmlReader
        );
    }

    /**
     * Create a player injury reader.
     *
     * @return Player\InjuryReaderInterface
     */
    public function createPlayerInjuryReader()
    {
        return new Player\InjuryReader(
            $this->xmlReader
        );
    }

    /**
     * Create a player statistics reader.
     *
     * @return Player\StatisticsReaderInterface
     */
    public function createPlayerStatisticsReader()
    {
        return new Player\StatisticsReader(
            $this->xmlReader
        );
    }

    private $urlBuilder;
    private $xmlReader;
}
