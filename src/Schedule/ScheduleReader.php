<?php

namespace Icecave\Siphon\Schedule;

use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamFactoryTrait;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading schedule feeds.
 */
class ScheduleReader implements ScheduleReaderInterface
{
    use CompetitionFactoryTrait;
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
        } elseif (ScheduleType::FULL() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league()
            );
        } elseif (ScheduleType::DELETED() === $request->type()) {
            $resource = sprintf(
                '/sport/v2/%s/%s/games-deleted/games_deleted_%s.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league()
            );
        } else {
            $resource = sprintf(
                '/sport/v2/%s/%s/schedule/schedule_%s_%d_days.xml',
                $request->sport()->sport(),
                $request->sport()->league(),
                $request->sport()->league(),
                $request->type()->value()
            );
        }

        $response = new ScheduleResponse($request->sport(), $request->type());

        return $this->xmlReader->read($resource)->then(
            function ($xml) use ($request, $response) {
                $xml = $xml->xpath('.//season-content');

                foreach ($xml as $element) {
                    $season = $this->createSeason($element->season);

                    foreach ($element->competition as $competitionElement) {
                        $competition = $this->createCompetition(
                            $competitionElement,
                            $request->sport(),
                            $season
                        );

                        foreach (
                            $competitionElement->xpath('.//player') as
                                $playerElement
                        ) {
                            $competition->addNotablePlayer(
                                $this->createPlayer($playerElement)
                            );
                        }

                        $season->add($competition);
                    }

                    $response->add($season);
                }

                return $response;
            }
        )->otherwise(
            function ($exception) use ($response) {
                if ($exception instanceof NotFoundException) {
                    return $response;
                } else {
                    throw $exception;
                }
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
        return $request instanceof ScheduleRequest;
    }

    private $xmlReader;
}
