<?php

namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Reader\Exception\NotFoundException;
use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\RequestUrlBuilderInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use React\Promise;
use SimpleXMLElement;

/**
 * Client for reading live score feeds.
 */
class LiveScoreReader implements LiveScoreReaderInterface
{
    use CompetitionFactoryTrait;
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
                list($xml, $modifiedTime) = $result;

                $xml = $xml->xpath('.//competition');

                // Sometimes the feed contains no information at all.
                // Since this information is required to build a meaningful
                // response, we treat this condition equivalent to a not found
                // error.
                if (!$xml) {
                    throw new NotFoundException();
                }

                $xml = $xml[0];

                $competition = $this->createCompetition($xml, $request->sport());
                $score = $this->createScore($xml, $request->sport());

                $response = new LiveScoreResponse(
                    $competition,
                    $score
                );
                $response->setModifiedTime($modifiedTime);

                if (
                    !$competition->status()->anyOf(
                        CompetitionStatus::POSTPONED(),
                        CompetitionStatus::SHORTENED(),
                        CompetitionStatus::CANCELLED(),
                        CompetitionStatus::COMPLETE()
                    )
                ) {
                    $scope = $xml->{'result-scope'}->scope;

                    foreach ($score as $period) {
                        $number = intval($scope['num']);
                        $type = $this->createPeriodType(
                            $request->sport(),
                            strval($scope['type'])
                        );

                        if (
                            $type === $period->type() &&
                            $number === $period->number()
                        ) {
                            $response->setCurrentPeriod($period);

                            break;
                        }
                    }
                }

                $gameTime = XPath::stringOrNull($xml, './/clock');

                if ($gameTime !== null) {
                    list($hours, $minutes, $seconds) = explode(':', $gameTime);

                    $response->setGameTime(
                        Duration::fromComponents(
                            0,
                            0,
                            intval($hours),
                            intval($minutes),
                            intval($seconds)
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
        return $request instanceof LiveScoreRequest;
    }

    /**
     * Create score from statistics data.
     *
     * @param SimpleXMLElement $element The competition element.
     * @param Sport            $sport
     *
     * @return Score
     */
    private function createScore(
        SimpleXMLElement $element,
        Sport $sport
    ) {
        $periods = [];
        $homeScore = 0;
        $awayScore = 0;

        $this->extractScores(
            $element->{'home-team-content'},
            $sport,
            $homeScore,
            $periods,
            'home'
        );

        $this->extractScores(
            $element->{'away-team-content'},
            $sport,
            $awayScore,
            $periods,
            'away'
        );

        $result = [];

        foreach ($periods as $period) {
            $result[] = new Period(
                $period->type,
                $period->number,
                $period->home,
                $period->away
            );
        }

        return new Score($homeScore, $awayScore, $result);
    }

    /**
     * Populate an array with scores from stats nodes.
     *
     * @param SimpleXMLElement      $element The home team or away team content element.
     * @param Sport                 $sport
     * @param integer               &$competitionScore
     * @param array<string, object> &$result The result array which scores are pushed into.
     * @param string                $team    One of 'home' or 'away'
     */
    private function extractScores(
        SimpleXMLElement $element,
        Sport $sport,
        &$competitionScore,
        array &$result,
        $team
    ) {
        // The score stat type is named "runs" for baseball, and "score" for
        // other sport types ...
        if ('baseball' === $sport->sport()) {
            $statPath = "stat[@type='runs']";
        } else {
            $statPath = "stat[@type='score']";
        }

        // Iterate over the stats and yield scores for each period-type / number ...
        foreach ($element->{'stat-group'} as $group) {
            // Look for the appropriate score statistic ...
            $score = XPath::elementOrNull($group, $statPath);

            if (null === $score) {
                continue;
            }

            // The 'competition' scope is not a period ...
            if ('competition' === strval($group->scope['type'])) {
                $competitionScore = intval($score['num']);
            } else {
                $key = $group->scope['type'] . $group->scope['num'];

                if (!isset($result[$key])) {
                    $result[$key] = (object) [
                        'type' => $this->createPeriodType(
                            $sport,
                            strval($group->scope['type'])
                        ),
                        'number' => intval($group->scope['num']),
                        'home'   => 0,
                        'away'   => 0,
                    ]; // @codeCoverageIgnore
                }

                $result[$key]->{$team} += intval($score['num']);
            }
        }
    }

    private function createPeriodType(Sport $sport, $type)
    {
        if ($type === 'period') {
            return PeriodType::memberBySport($sport);
        }

        return PeriodType::memberBySportAndValue($sport, $type);
    }

    private $urlBuilder;
    private $xmlReader;
}
