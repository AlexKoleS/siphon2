<?php

namespace Icecave\Siphon\Player;

use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading player feeds.
 */
class PlayerReader implements PlayerReaderInterface
{
    use PlayerFactoryTrait;
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

                // Sometimes the feed contains no team or player information.
                // Since this information is required to build a meaningful
                // response, we treat this condition equivalent to a not found
                // error.
                if (!$xml->{'team-content'}) {
                    throw new NotFoundException();
                }

                $response = new PlayerResponse(
                    $request->sport(),
                    $this->createSeason($xml->season),
                    $this->createTeam($xml->{'team-content'}->team)
                );

                foreach ($xml->xpath('.//player') as $player) {
                    $response->add(
                        $this->createPlayer($player),
                        new PlayerSeasonDetails(
                            XPath::stringOrNull(
                                $player,
                                'season-details/number'
                            ),
                            XPath::string(
                                $player,
                                'season-details/position' .
                                    "[@type='primary']/name[@type='short']"
                            ),
                            XPath::string(
                                $player,
                                'season-details/position' .
                                    "[@type='primary']/name[count(@type)=0]"
                            ),
                            XPath::string($player, 'season-details/active') ===
                                'true'
                        )
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
        return $request instanceof PlayerRequest;
    }

    private $urlBuilder;
    private $xmlReader;
}
