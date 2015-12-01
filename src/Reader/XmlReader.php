<?php

namespace Icecave\Siphon\Reader;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\Exception\ServiceUnavailableException;
use React\Promise\Deferred;
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
     * Read XML data from a feed.
     *
     * @param string               $resource   The path to the feed.
     * @param array<string, mixed> $parameters Additional parameters to pass.
     *
     * @return SimpleXMLElement [via promise] The XML response.
     */
    public function read($resource, array $parameters = [])
    {
        $url = $this->urlBuilder->build($resource, $parameters);
        $deferred = new Deferred();

        $this->httpClient->requestAsync('GET', $url)->then(
            function ($response) use ($deferred) {
                $deferred->resolve(
                    new SimpleXMLElement($response->getBody(), LIBXML_NONET)
                );
            }
        )->otherwise(
            function ($exception) use ($deferred) {
                if (
                    $exception instanceof ClientException &&
                    404 === $exception->getCode()
                ) {
                    $deferred->reject(new NotFoundException($exception));
                } else {
                    $deferred->reject(
                        new ServiceUnavailableException($exception)
                    );
                }
            }
        );

        return $deferred->promise();
    }

    private $urlBuilder;
    private $httpClient;
}
