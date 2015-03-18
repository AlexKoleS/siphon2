<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use SimpleXMLElement;

class PeriodFactory implements ResultFactoryInterface
{
    /**
     * @param StatisticsAggregator|null $statisticsAggregator
     */
    public function __construct(StatisticsAggregator $statisticsAggregator = null)
    {
        $this->statisticsAggregator = $statisticsAggregator ?: new StatisticsAggregator;
    }

    /**
     * Check if this factory supports creation of live scores for the given
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
     * Create a live-score object from the given XML document.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return LiveScoreInterface
     */
    public function create(
        $sport,
        $league,
        SimpleXMLElement $xml
    ) {
        $result = new PeriodResult;

        $this->updateCompetitionStatus($xml, $result);
        $this->updateCompetitionScore($xml, $result);
        $this->updateGameTime($xml, $result);

        return $result;
    }

    private function updateCompetitionStatus(SimpleXMLElement $xml, PeriodResult $result)
    {
        $result->setCompetitionStatus(
            CompetitionStatus::memberByValue(
                strval($xml->xpath('//competition-status')[0])
            )
        );
    }

    private function updateCompetitionScore(SimpleXMLElement $xml, PeriodResult $result)
    {
        $resultScope = $xml->xpath('//result-scope')[0];

        if ('in-progress' === strval($resultScope->{'scope-status'})) {
            $currentType   = strval($resultScope->scope['type']);
            $currentNumber = intval($resultScope->scope['num']);
        } else {
            $currentType   = null;
            $currentNumber = null;
        }

        $score = new PeriodScore;
        $stats = $this->statisticsAggregator->extract($xml);

        foreach ($stats as $s) {
            $scope = new Period(
                PeriodType::memberByValue($s->type),
                $s->home['score'],
                $s->away['score']
            );

            $score->add($scope);

            if (
                $currentType === $s->type
                && $currentNumber === $s->number
            ) {
                $result->setCurrentScope($scope);
            }
        }

        $result->setCompetitionScore($score);
    }

    private function updateGameTime(SimpleXMLElement $xml, PeriodResult $result)
    {
        $clock = $xml->xpath('//result-scope/clock');

        if (!$clock) {
            return;
        }

        list($hours, $minutes, $seconds) = explode(':', current($clock));

        $result->setCurrentGameTime(
            Duration::fromComponents(0, 0, $hours, $minutes, $seconds)
        );
    }

    private $statisticsAggregator;
}
