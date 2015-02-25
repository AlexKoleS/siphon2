<?php
namespace Icecave\Siphon\LiveScore\Innings;

use Icecave\Siphon\LiveScore\LiveScoreFactoryInterface;
use Icecave\Siphon\LiveScore\LiveScoreInterface;
use Icecave\Siphon\LiveScore\ScopeStatus;
use Icecave\Siphon\LiveScore\StatisticsAggregator;
use Icecave\Siphon\LiveScore\StatisticsAggregatorInterface;
use Icecave\Siphon\Schedule\Competition;
use SimpleXMLElement;

class InningsLiveScoreFactory implements LiveScoreFactoryInterface
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
        return 'baseball' === $competition->sport();
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
        $result = new InningsLiveScore;
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
