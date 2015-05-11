<?php
namespace Icecave\Siphon\Atom;

use Icecave\Chrono\DateTime;
use Icecave\Siphon\Reader\ReaderInterface;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\ResponseInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use InvalidArgumentException;

class AtomReader implements ReaderInterface
{
    public function __construct(
        RequestFactoryInterface $requestFactory,
        XmlReaderInterface $xmlReader
    ) {
        $this->requestFactory = $requestFactory;
        $this->xmlReader      = $xmlReader;
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
        if (!$this->isSupported($request)) {
            throw new InvalidArgumentException('Unsupported request.');
        }

        $xml = $this->xmlReader->read(
            '/Atom',
            $this->buildParameters($request)
        );

        $response = new AtomResponse(
            DateTime::fromIsoString($xml->updated)
        );

        foreach ($xml->entry as $entry) {
            $url = strval($entry->link['href']);

            $request = $this
                ->requestFactory
                ->create($url);

            $response->add($request);
        }

        return $response;
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

    /**
     * Build the URL parameters for the given request.
     *
     * @param AtomRequest $request
     *
     * @return array
     */
    private function buildParameters(AtomRequest $request)
    {
        $parameters = [
            'newerThan' => $request->updatedTime()->isoString(),
            'maxCount'  => $request->limit(),
        ];

        if (SORT_ASC === $request->order()) {
            $parameters['order'] = 'asc';
        } else {
            $parameters['order'] = 'desc';
        }

        if (null !== $request->feed()) {
            $parameters['feed'] = $request->feed();
        }

        return $parameters;
    }

    private $requestFactory;
    private $xmlReader;
}
