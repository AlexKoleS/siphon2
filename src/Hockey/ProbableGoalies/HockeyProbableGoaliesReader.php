<?php

namespace Icecave\Siphon\Hockey\ProbableGoalies;

use Icecave\Siphon\Player\PlayerFactoryTrait;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Schedule\SeasonFactoryTrait;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;

/**
 * Client for reading live score feeds.
 */
class HockeyProbableGoaliesReader implements HockeyProbableGoaliesReaderInterface
{
    use SeasonFactoryTrait;
    use CompetitionFactoryTrait;
    use TeamFactoryTrait;
    use PlayerFactoryTrait;

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
                list($xml, $modifiedTime) = $result;

                $xml = $xml->xpath('.//season-content');

                $sport = $request->sport();
                $response = new HockeyProbableGoaliesResponse($sport);
                $response->setModifiedTime($modifiedTime);

                foreach ($xml as $element) {
                    $season = $this->createSeason($element->season);

                    foreach ($element->competition as $competitionElement) {
                        $competition = $this->createCompetition(
                            $competitionElement,
                            $sport,
                            $season
                        );

                        $homePlayers = $competitionElement
                            ->{'home-team-content'}->{'player-content'}->player;

                        if ($homePlayers) {
                            foreach ($homePlayers as $player) {
                                $response->add(
                                    $competition,
                                    $competition->homeTeam(),
                                    $this->createPlayer($player)
                                );
                            }
                        }

                        $awayPlayers = $competitionElement
                            ->{'away-team-content'}->{'player-content'}->player;

                        if ($awayPlayers) {
                            foreach ($awayPlayers as $player) {
                                $response->add(
                                    $competition,
                                    $competition->awayTeam(),
                                    $this->createPlayer($player)
                                );
                            }
                        }
                    }
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
        return $request instanceof HockeyProbableGoaliesRequest &&
            Sport::NHL() === $request->sport();
    }

    private $urlBuilder;
    private $xmlReader;
}
