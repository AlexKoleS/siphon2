<?php

namespace Icecave\Siphon\Player\Image;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading player image feeds.
 */
class ImageReader implements ImageReaderInterface
{
    use PlayerFactoryTrait;
    use SeasonFactoryTrait;
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
            '/sport/v2/%s/%s/player-images/%s/player-images_%d_%s.xml',
            $request->sport()->sport(),
            $request->sport()->league(),
            $request->seasonName(),
            $request->teamId(),
            $request->sport()->league()
        );

        return $this->xmlReader->read($resource)->then(
            function ($xml) use ($request) {
                $xml = $xml->xpath('.//season-content')[0];

                // Sometimes the feed contains no team or player information.
                // Since this information is required to build a meaningful
                // response, we treat this condition equivalent to a not found
                // error.
                if (!$xml->{'team-content'}) {
                    throw new NotFoundException();
                }

                $response = new ImageResponse(
                    $request->sport(),
                    $this->createSeason($xml->season),
                    $this->createTeam($xml->{'team-content'}->team)
                );

                foreach ($xml->xpath('.//player-content') as $element) {
                    $small =
                        XPath::stringOrNull($element, 'image/thumbnailurl');
                    $large = XPath::stringOrNull($element, 'image/url');

                    if (null === $small && null === $large) {
                        continue;
                    }

                    $response->add(
                        $this->createPlayer($element->player),
                        $small,
                        $large
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
        return $request instanceof ImageRequest;
    }
}
