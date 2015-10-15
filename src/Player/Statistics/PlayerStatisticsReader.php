<?php

namespace Icecave\Siphon\Player\Statistics;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Statistics\StatisticsFactoryTrait;
use Icecave\Siphon\Statistics\StatisticsType;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;

/**
 * Client for reading player statistics feeds.
 */
class PlayerStatisticsReader implements PlayerStatisticsReaderInterface
{
    use PlayerFactoryTrait;
    use SeasonFactoryTrait;
    use StatisticsFactoryTrait;
    use TeamFactoryTrait;

    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
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
        } elseif (StatisticsType::COMBINED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/player-stats/%s/player_stats_%d_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->seasonName(),
                $request->teamId(),
                $request->sport()->league()
            );
        } else { // split stats
            $resource = sprintf(
                '/sport/v2/%s/%s/player-split-stats/%s/player_split_stats_%d_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->seasonName(),
                $request->teamId(),
                $request->sport()->league()
            );
        }

        $xml = $this
            ->xmlReader
            ->read($resource)
            ->xpath('.//season-content')[0];

        // Sometimes the feed contains no team or player information. Since
        // this information is required to build a meaningful response, we treat
        // this condition equivalent to a not found error.
        if (!$xml->{'team-content'}) {
            throw new NotFoundException();
        }

        $response = new PlayerStatisticsResponse(
            $request->sport(),
            $this->createSeason($xml->season),
            $this->createTeam($xml->{'team-content'}->team),
            $request->type()
        );

        foreach ($xml->xpath('.//player-content') as $element) {
            $response->add(
                $this->createPlayer($element->player),
                $this->createStatisticsCollection($element)
            );
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
        return $request instanceof PlayerStatisticsRequest;
    }

    private $xmlReader;
}
