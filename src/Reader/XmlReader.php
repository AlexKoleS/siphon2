<?php

namespace Icecave\Siphon\Reader;

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\Request;
use Clue\React\Buzz\Message\ResponseException;
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
        Browser $httpClient
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

        return $this->httpClient->get($url)
            ->then(
                function ($response) {
                    return new SimpleXMLElement($response->getBody(), LIBXML_NONET);
                }
            )->otherwise(
                function ($exception) {
                    if (
                        $exception instanceof ResponseException &&
                        404 === $exception->getResponse()->getCode()
                    ) {
                       throw new NotFoundException($exception);
                    }

                    throw new ServiceUnavailableException($exception);
                }
            );
    }

    private $urlBuilder;
    private $httpClient;
}
