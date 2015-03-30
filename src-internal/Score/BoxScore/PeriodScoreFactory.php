<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\XPath;
use SimpleXMLElement;

class PeriodScoreFactory implements ScoreFactoryInterface
{
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
        return 'baseball' === $sport
            && 'MLB' === $league;
    }

    /**
     * Create a score object from the given XML document.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return ScoreInterface
     */
    public function create($sport, $league, SimpleXMLElement $xml)
    {
        $result = new InningScore;



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

        $score = new InningScore;
        $stats = $this->statisticsAggregator->extract($xml);

        foreach ($stats as $s) {
            $scope = new Inning(
                $s->home['runs'],
                $s->away['runs'],
                $s->home['hits'],
                $s->away['hits'],
                $s->home['errors'],
                $s->away['errors']
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

    private $statisticsAggregator;
}
