<?php
namespace Icecave\Siphon;

use GuzzleHttp\ClientInterface;
use SimpleXMLElement;

/**
 * Fetch XML data from a feed.
 */
class XmlReader implements XmlReaderInterface
{
    public function __construct(
        UrlBuilderInterface $urlBuilder,
        ClientInterface $httpClient
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->httpClient = $httpClient;
    }

    /**
     * Read an XML feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement The XML response.
     */
    public function read($resource, array $parameters = [])
    {
        $url      = $this->urlBuilder->build($resource, $parameters);
        $response = $this->httpClient->get($url);

        return $response->xml();
    }

    private $urlBuilder;
    private $httpClient;
}
