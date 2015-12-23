<?php

namespace Icecave\Siphon\Result;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading result feeds.
 */
class ResultReader implements ResultReaderInterface
{
    use CompetitionFactoryTrait;
    use SeasonFactoryTrait;
    use TeamFactoryTrait;

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
            function ($xml) use ($request) {
                $xml = $xml->xpath('.//season-content')[0];
                $season = $this->createSeason($xml->season);
                $sport = $request->sport();
                $response = new ResultResponse($sport, $season);

                foreach ($xml->xpath('.//competition') as $competition) {
                    $qaStatus = XPath::stringOrNull(
                        $competition,
                        "//meta/property[@name='qa-status']"
                    );

                    $response->add(
                        $this->createCompetition($competition, $sport, $season),
                        'finalized' === $qaStatus
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
        return $request instanceof ResultRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
