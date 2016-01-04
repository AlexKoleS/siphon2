<?php

namespace Icecave\Siphon\Team\Statistics;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsFactoryTrait;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading team statistics feeds.
 */
class TeamStatisticsReader implements TeamStatisticsReaderInterface
{
    use PlayerFactoryTrait;
    use SeasonFactoryTrait;
    use StatisticsFactoryTrait;
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

                // Sometimes the feed contains no team or player information.
                // Since this information is required to build a meaningful
                // response, we treat this condition equivalent to a not found
                // error.
                if (!$teamContent = $xml->xpath('.//team-content')) {
                    throw new NotFoundException();
                }

                $response = new TeamStatisticsResponse(
                    $request->sport(),
                    $this->createSeason($xml->season),
                    $request->type()
                );

                foreach ($teamContent as $element) {
                    $response->add(
                        $this->createTeam($element->team),
                        $this->createStatisticsCollection($element)
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
        if (!$request instanceof TeamStatisticsRequest) {
            return false;
        } elseif ($request->sport()->anyOf(Sport::NCAAF(), Sport::NCAAB())) {
            return StatisticsType::SPLIT() !== $request->type();
        }

        return true;
    }

    private $urlBuilder;
    private $xmlReader;
}
