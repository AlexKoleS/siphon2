<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Reader\RequestInterface;
use Icecave\Siphon\Reader\XmlReaderInterface;
use Icecave\Siphon\Schedule\CompetitionFactoryTrait;
use Icecave\Siphon\Score\Period\Period;
use Icecave\Siphon\Score\Period\PeriodType;
use Icecave\Siphon\Score\Score;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Team\TeamFactoryTrait;
use Icecave\Siphon\Util\XPath;
use InvalidArgumentException;
use SimpleXMLElement;

/**
 * Client for reading live score feeds.
 */
class LiveScoreReader implements LiveScoreReaderInterface
{
    use CompetitionFactoryTrait;
    use TeamFactoryTrait;

    /**
     * @param XMLReaderInterface $xmlReader
     */
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
                    '/sport/v2/%s/%s/livescores/livescores_%d.xml',
                    $request->sport()->sport(),
                    $request->sport()->league(),
                    $request->competitionId()
                )
            )
            ->xpath('.//competition')[0];

        return new LiveScoreResponse(
            $this->createCompetition(
                $xml,
                $request->sport()
            ),
            new Score(
                $this->createPeriods(
                    $xml,
                    $request->sport()
                )
            )
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
     * Create periods from statistics data.
     *
     * @param SimpleXMLElement $element The competition element.
     * @param Sport            $sport
     *
     * @return array<Period>
     */
    private function createPeriods(
        SimpleXMLElement $element,
        Sport $sport
    ) {
        $periods = [];

        $this->extractScores(
            $element->{'home-team-content'},
            $sport,
            $periods,
            'home'
        );

        $this->extractScores(
            $element->{'away-team-content'},
            $sport,
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

        return $result;
    }

    /**
     * Populate an array with scores from stats nodes.
     *
     * @param SimpleXMLElement      $element The home team or away team content element.
     * @param Sport                 $sport
     * @param array<string, object> &$result The result array which scores are pushed into.
     * @param string                $team    One of 'home' or 'away'
     */
    private function extractScores(
        SimpleXMLElement $element,
        Sport $sport,
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
            // The 'competition' scope is not a period ...
            if ('competition' === strval($group->scope['type'])) {
                continue;
            }

            $key = $group->scope['type'] . $group->scope['num'];

            if (!isset($result[$key])) {
                $result[$key] = (object) [
                    'type' => PeriodType::memberBySportAndValue(
                        $sport,
                        strval($group->scope['type'])
                    ),
                    'number' => intval($group->scope['num']),
                    'home'   => 0,
                    'away'   => 0,
                ]; // @codeCoverageIgnore
            }

            // Look for the appropriate score statistic ...
            $score = XPath::elementOrNull($group, $statPath);

            if ($score) {
                $result[$key]->{$team} += intval($score['num']);
            }
        }
    }

    private $xmlReader;
}
