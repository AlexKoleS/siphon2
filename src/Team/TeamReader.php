<?php

namespace Icecave\Siphon\Team;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\UrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading team feeds.
 */
class TeamReader implements TeamReaderInterface
{
    use SeasonFactoryTrait;
    use TeamFactoryTrait;

    public function __construct(
        UrlBuilderInterface $urlBuilder,
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

        $resource = sprintf(
            '/sport/v2/%s/%s/teams/%s/teams_%s.xml',
            $request->sport()->sport(),
            $request->sport()->league(),
            $request->seasonName(),
            $request->sport()->league()
        );
        $url = $this->urlBuilder->build($resource, array(), false);

        return $this->xmlReader->read($resource)->then(
            function ($xml) use ($request, $url) {
                $xml = $xml->xpath('.//season-content')[0];
                $response = new TeamResponse(
                    $request->sport(),
                    $this->createSeason($xml->season)
                );
                $response->setUrl($url);

                foreach ($xml->xpath('.//team') as $team) {
                    $response->add($this->createTeam($team));
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
        return $request instanceof TeamRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
