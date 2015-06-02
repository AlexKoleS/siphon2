<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;

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
     * @return ResponseInterface        The response.
     * @throws InvalidArgumentException if the request is not supported.
     */
    public function read(RequestInterface $request)
    {
        if (!$this->isSupported($request)) {
            throw new InvalidArgumentException('Unsupported request.');
        }

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/player-images/%s/player-images_%d_%s.xml',
                    $request->sport()->sport(),
                    $request->sport()->league(),
                    $request->seasonName(),
                    $request->teamId(),
                    $request->sport()->league()
                )
            )
            ->xpath('.//season-content')[0];

        // Sometimes the feed contains no team or player information. Since
        // this information is required to build a meaningful response, we treat
        // this condition equivalent to a not found error.
        if (!$xml->{'team-content'}) {
            throw new NotFoundException;
        }

        $response = new ImageResponse(
            $request->sport(),
            $this->createSeason($xml->season),
            $this->createTeam($xml->{'team-content'}->team)
        );

        foreach ($xml->xpath('.//player-content') as $element) {
            $response->add(
                $this->createPlayer($element->player),
                XPath::string($element, 'image/url'),
                XPath::string($element, 'image/thumbnailurl')
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
        return $request instanceof ImageRequest;
    }
}
