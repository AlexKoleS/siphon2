<?php

namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Util\URL;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading atom feeds.
 */
class AtomReader implements AtomReaderInterface
{
    public function __construct(
        RequestUrlBuilderInterface $urlBuilder,
        XmlReaderInterface $xmlReader
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->xmlReader = $xmlReader;
    }

    /**
     * Make a request and return the response.
     *
     * @param RequestInterface The request.
     *
     * @return ResponseInterface        [via promise] The response.
     * @throws InvalidArgumentException [via promise] If the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        if (!$this->isSupported($request)) {
            return Promise\reject(
                new InvalidArgumentException('Unsupported request.')
            );
        }

        return $this->xmlReader->read($this->urlBuilder->build($request))->then(
            function ($xml) {
                $response = new AtomResponse(strval($xml->updated));

                foreach ($xml->entry as $entry) {
                    $response->add(
                        URL::stripParameter(
                            strval($entry->link['href']),
                            'apiKey'
                        ),
                        DateTime::fromIsoString($entry->updated)
                    );
                }

                return $response;
            }
        );
    }

    /**
     * Check if the given request is supported.
     *
     * @return boolean True if the given request is supported; otherwise, false.
     */
    public function isSupported(RequestInterface $request)
    {
        return $request instanceof AtomRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
