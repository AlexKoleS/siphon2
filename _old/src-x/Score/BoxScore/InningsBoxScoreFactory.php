<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\Innings;
use Icecave\Siphon\Score\InningsScore;
use Icecave\Siphon\Score\InningsType;
use Icecave\Siphon\Score\ScopeStatus;
use SimpleXMLElement;

class InningsBoxScoreFactory implements BoxScoreFactoryInterface
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
        return 'baseball' === $sport;
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
        $result = new InningsScore;
        $stats  = $this->statisticsAggregator->extract($xml);
        $scope  = null;

        foreach ($stats as $s) {
            $scope = new Innings(
                $s->home['runs'],
                $s->away['runs'],
                $s->home['hits'],
                $s->away['hits'],
                $s->home['errors'],
                $s->away['errors']
            );

            $result->add($scope);
        }

        if ($scope) {
            $resultScope = $xml->xpath('//result-scope')[0];

            $status = ScopeStatus::memberByValue(
                strval($resultScope->{'scope-status'})
            );

            $scope->setStatus($status);

            // If the current scope is in progress then set the current innings type ...
            if (ScopeStatus::IN_PROGRESS() === $status) {
                $result->setCurrentInningsType(
                    InningsType::memberByValue(
                        strval($resultScope->scope['sub-type'])
                    )
                );
            }
        }

        return $result;
    }

    private $statisticsAggregator;
}
