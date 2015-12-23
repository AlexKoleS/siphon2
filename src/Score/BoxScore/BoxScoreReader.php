<?php

namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsFactoryTrait;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading live score feeds.
 */
class BoxScoreReader implements BoxScoreReaderInterface
{
    use CompetitionFactoryTrait;
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

                $competition = $this->createCompetition(
                    $xml->competition,
                    $request->sport(),
                    $this->createSeason($xml->season)
                );

                $response = new BoxScoreResponse(
                    $competition,
                    $this->createStatisticsCollection(
                        $xml->competition->{'home-team-content'}
                    ),
                    $this->createStatisticsCollection(
                        $xml->competition->{'away-team-content'}
                    )
                );

                $qaStatus = XPath::stringOrNull(
                    $xml,
                    ".//competition/meta/property[@name='qa-status']"
                );

                if ('finalized' === $qaStatus) {
                    $response->setIsFinalized(true);
                }

                foreach (
                    $xml->xpath('.//home-team-content/player-content') as
                    $element
                ) {
                    $response->add(
                        $competition->homeTeam(),
                        $this->createPlayer($element->player),
                        $this->createStatisticsCollection($element)
                    );
                }

                foreach (
                    $xml->xpath('.//away-team-content/player-content') as
                    $element
                ) {
                    $response->add(
                        $competition->awayTeam(),
                        $this->createPlayer($element->player),
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
        return $request instanceof BoxScoreRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
