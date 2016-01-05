<?php

namespace Icecave\Siphon\Schedule;

use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
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
            function ($result) use ($request) {
                list($xml, $lastModified) = $result;
                $xml = $xml->xpath('.//season-content');
                $response =
                    new ScheduleResponse($request->sport(), $request->type());

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

    private $urlBuilder;
    private $xmlReader;
}
