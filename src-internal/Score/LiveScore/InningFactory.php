<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\XPath;
use SimpleXMLElement;

class InningFactory implements ResultFactoryInterface
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
        return 'baseball' === $sport;
    }

    /**
     * Create a live score for the given competition.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return LiveScoreInterface
     */
    public function create($sport, $league, SimpleXMLElement $xml)
    {
        $result = new InningResult;

        $this->updateCompetitionStatus($xml, $result);
        $this->updateCompetitionScore($xml, $result);

        return $result;
    }

    private function updateCompetitionStatus(SimpleXMLElement $xml, InningResult $result)
    {
        $result->setCompetitionStatus(
            CompetitionStatus::memberByValue(
                XPath::string($xml, '//competition-status')
            )
        );
    }

    private function updateCompetitionScore(SimpleXMLElement $xml, InningResult $result)
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

            $result->setCurrentInningSubType(
                InningSubType::memberByValue(
                    strval($resultScope->scope['sub-type'])
                )
            );
        } else {
            $currentType   = null;
            $currentNumber = null;
            $scopeStatus   = null;
        }

        $stats          = $this->statisticsAggregator->extract($xml);
        $innings        = [];
        $homeTeamHits   = 0;
        $awayTeamHits   = 0;
        $homeTeamErrors = 0;
        $awayTeamErrors = 0;

        foreach ($stats as $s) {
            $scope = new Inning(
                $s->home['runs'],
                $s->away['runs']
            );

            if (
                $currentType === $s->type
                && $currentNumber === $s->number
            ) {
                $result->setCurrentScope($scope);
            }

            $innings[] = $scope;

            $homeTeamHits   += $s->home['hits'];
            $awayTeamHits   += $s->away['hits'];
            $homeTeamErrors += $s->home['errors'];
            $awayTeamErrors += $s->away['errors'];
        }

        $score = new InningScore(
            $homeTeamHits,
            $awayTeamHits,
            $homeTeamErrors,
            $awayTeamErrors
        );

        foreach ($innings as $inning) {
            $score->add($inning);
        }

        $result->setCompetitionScore($score);
    }

    private $statisticsAggregator;
}
