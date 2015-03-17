<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Score\ScopeStatus;
use SimpleXMLElement;

class PeriodBoxScoreFactory implements BoxScoreFactoryInterface
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
     * Check if this factory supports creation of boxscores for the given
     * competition.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return boolean True if the factory supports the given competition; otherwise, false.
     */
    public function supports($sport, $league)
    {
        return in_array(
            $sport,
            [
                'football',
                'basketball',
                'hockey',
            ]
        );
    }

    /**
     * Create a boxscore for the given competition.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return ScoreInterface
     */
    public function create($sport, $league, SimpleXMLElement $xml)
    {
        $result = new PeriodScore;
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

        $resultScope = $xml->xpath('//result-scope')[0];

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
