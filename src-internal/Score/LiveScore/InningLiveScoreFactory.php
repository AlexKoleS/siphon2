<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Schedule\CompetitionStatus;
use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use SimpleXMLElement;

class InningLiveScoreFactory implements LiveScoreFactoryInterface
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
        $liveScore = new InningLiveScore;

        $this->updateCompetitionStatus($xml, $liveScore);
        $this->updateCompetitionScore($xml, $liveScore);

        return $liveScore;
    }

    private function updateCompetitionStatus(SimpleXMLElement $xml, InningLiveScore $liveScore)
    {
        $liveScore->setCompetitionStatus(
            CompetitionStatus::memberByValue(
                strval($xml->xpath('//competition-status')[0])
            )
        );
    }

    private function updateCompetitionScore(SimpleXMLElement $xml, InningLiveScore $liveScore)
    {
        $resultScope = $xml->xpath('//result-scope')[0];

        if ('in-progress' === strval($resultScope->{'scope-status'})) {
            $currentType   = strval($resultScope->scope['type']);
            $currentNumber = intval($resultScope->scope['num']);

            $liveScore->setCurrentInningSubType(
                InningSubType::memberByValue(
                    strval($resultScope->scope['sub-type'])
                )
            );
        } else {
            $currentType   = null;
            $currentNumber = null;
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
                $liveScore->setCurrentScope($scope);
            }
        }

        $liveScore->setCompetitionScore($score);
    }

    private $statisticsAggregator;
}
