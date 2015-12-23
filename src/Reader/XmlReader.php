<?php

namespace Icecave\Siphon\Reader;

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\ResponseException;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\Exception\ServiceUnavailableException;
use SimpleXMLElement;

/**
 * Reads XML data.
 */
class XmlReader implements XmlReaderInterface
{
    /**
     * @param ClientInterface $httpClient The HTTP client used to fetch XML data.
     */
    public function __construct(Browser $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Read XML data from a URL.
     *
     * @param string $url The URL.
     *
     * @return SimpleXMLElement [via promise] The XML response.
     */
    public function read($url)
    {
        return $this->httpClient->get($url)
            ->then(
                function ($response) {
                    return new SimpleXMLElement(
                        $response->getBody(),
                        LIBXML_NONET
                    );
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

    private $httpClient;
}
