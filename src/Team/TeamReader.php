<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;

/**
 * Client for reading team feeds.
 */
class TeamReader implements TeamReaderInterface
{
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
                    '/sport/v2/%s/%s/teams/%s/teams_%s.xml',
                    $request->sport()->sport(),
                    $request->sport()->league(),
                    $request->seasonName(),
                    $request->sport()->league()
                )
            )
            ->xpath('.//season-content')[0];

        $season = $this->createSeason($xml->season);

        $response = new TeamResponse(
            $request->sport(),
            $season
        );

        foreach ($xml->xpath('.//team') as $team) {
            $response->add(
                $this->createTeam($team)
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
        return $request instanceof TeamRequest;
    }

    private $xmlReader;
}
