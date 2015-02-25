<?php
namespace Icecave\Siphon\LiveScore\Period;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\LiveScore\LiveScoreFactoryInterface;
use Icecave\Siphon\LiveScore\ScopeStatus;
use Icecave\Siphon\LiveScore\StatisticsAggregator;
use Icecave\Siphon\LiveScore\StatisticsAggregatorInterface;
use Icecave\Siphon\Schedule\Competition;
use SimpleXMLElement;

class PeriodLiveScoreFactory implements LiveScoreFactoryInterface
{
    /**
     * @param StatisticsAggregatorInterface|null $statisticsAggregator
     */
    public function __construct(StatisticsAggregatorInterface $statisticsAggregator = null)
    {
        if (null === $statisticsAggregator) {
            $statisticsAggregator = new StatisticsAggregator;
        }

        $this->statisticsAggregator = $statisticsAggregator;
    }

    /**
     * Check if this factory supports creation of live scores for the given
     * competition.
     *
     * @param Competition $competition The competition.
     *
     * @return boolean True if the factory supports the given competition; otherwise, false.
     */
    public function supports(Competition $competition)
    {
        return in_array(
            $competition->sport(),
            [
                'football',
                'basketball',
                'hockey',
            ]
        );
    }

    /**
     * Create a live score for the given competition.
     *
     * @param Competition      $competition The competition.
     * @param SimpleXMLElement $xml         The XML document being parsed.
     *
     * @return LiveScoreInterface
     */
    public function create(
        Competition $competition,
        SimpleXMLElement $xml
    ) {
        $result = new PeriodLiveScore;
        $stats  = $this->statisticsAggregator->extract($xml);
        $scope  = null;

        foreach ($stats as $s) {
            $scope = new Period(
                $s->home['score'],
                $s->away['score']
            );

            $scope->setType(
                PeriodType::memberByValue($s->type)
            );

            $result->add($scope);
        }

        $resultScope = $xml
            ->{'team-sport-content'}
            ->{'league-content'}
            ->{'competition'}
            ->{'result-scope'};

        if ($scope) {
            $status = ScopeStatus::memberByValue(
                strval($resultScope->{'scope-status'})
            );

            $scope->setStatus($status);
        }

        if ($resultScope->clock) {
            list($hours, $minutes, $seconds) = explode(':', $resultScope->clock);

            $result->setGameTime(
                Duration::fromComponents(0, 0, $hours, $minutes, $seconds)
            );
        }

        return $result;
    }

    private $statisticsAggregator;
}
