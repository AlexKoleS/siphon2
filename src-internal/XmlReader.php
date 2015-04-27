<?php
namespace Icecave\Siphon;

use Exception;
use GuzzleHttp\ClientInterface;
use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Exception\ServiceUnavailableException;
use SimpleXMLElement;

/**
 * Reads XML from a feed.
 */
class XmlReader implements XmlReaderInterface
{
    /**
     * @param UrlBuilderInterface $urlBuilder The URL builder used to resolve feed URLs.
     * @param ClientInterface     $httpClient The HTTP client used to fetch XML data.
     */
    public function __construct(
        UrlBuilderInterface $urlBuilder,
        ClientInterface $httpClient
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->httpClient = $httpClient;
    }

    /**
     * Fetch XML data from a feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement The XML response.
     */
    public function read($resource, array $parameters = [])
    {
        $url = $this->urlBuilder->build($resource, $parameters);

        try {
            $response = $this->httpClient->get($url);
        } catch (Exception $e) {
            throw new ServiceUnavailableException(
                $e->getMessage(),
                0,
                $e
            );
        }

        return $response->xml();
    }

    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return mixed
     */
    public function readAtomEntry(AtomEntry $atomEntry)
    {
        return $this->read(
            $atomEntry->resource(),
            $atomEntry->parameters()
        );
    }

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry   The atom entry.
     * @param array     &$parameters Populated with reader-specific parameters represented by the atom entry.
     *
     * @return boolean
     */
    public function supportsAtomEntry(
        AtomEntry $atomEntry,
        array &$parameters = []
    ) {
        return true;
    }

    private $urlBuilder;
    private $httpClient;
}