<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Chrono\TimeSpan\Duration;
use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\XPath;
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
                XPath::string($xml, '//competition-status')
            )
        );
    }

    private function updateCompetitionScore(SimpleXMLElement $xml, PeriodResult $result)
    {
        $resultScope = XPath::element($xml, '//result-scope');

        if (CompetitionStatus::COMPLETE() !== $result->competitionStatus()) {
            $currentType   = strval($resultScope->scope['type']);
            $currentNumber = intval($resultScope->scope['num']);

            $result->setCurrentScopeStatus(
                ScopeStatus::memberByValue(
                    strval($resultScope->{'scope-status'})
                )
            );
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
        $clock = XPath::stringOrNull($xml, '//result-scope/clock');

        if (null === $clock) {
            return;
        }

        list($hours, $minutes, $seconds) = explode(':', $clock);

        $result->setCurrentGameTime(
            Duration::fromComponents(0, 0, $hours, $minutes, $seconds)
        );
    }

    private $statisticsAggregator;
}
