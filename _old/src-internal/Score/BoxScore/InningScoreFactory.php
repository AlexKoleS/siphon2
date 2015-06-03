<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\Inning;
use Icecave\Siphon\Score\InningScore;
use Icecave\Siphon\Util;
use Icecave\Siphon\XPath;
use SimpleXMLElement;

class InningScoreFactory implements ScoreFactoryInterface
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
        $homeTeamStats = Util::extractStatisticsGroups(XPath::element($xml, '//home-team-content'));
        $awayTeamStats = Util::extractStatisticsGroups(XPath::element($xml, '//away-team-content'));

        $result = new InningScore(
            isset($homeTeamStats['results']['hits'])   ? $homeTeamStats['results']['hits']   : 0,
            isset($awayTeamStats['results']['hits'])   ? $awayTeamStats['results']['hits']   : 0,
            isset($homeTeamStats['results']['errors']) ? $homeTeamStats['results']['errors'] : 0,
            isset($awayTeamStats['results']['errors']) ? $awayTeamStats['results']['errors'] : 0
        );

        $inningCount    = 9;
        $homeTeamScores = $this->extractScores($homeTeamStats, $inningCount);
        $awayTeamScores = $this->extractScores($awayTeamStats, $inningCount);

        for ($index = 1; $index <= $inningCount; ++$index) {
            $result->add(
                new Inning(
                    isset($homeTeamScores[$index]) ? $homeTeamScores[$index] : 0,
                    isset($awayTeamScores[$index]) ? $awayTeamScores[$index] : 0
                )
            );
        }

        return $result;
    }

    private function extractScores(array $stats, &$inningCount)
    {
        $result = [];

        if (isset($stats['game-stats'])) {
            foreach ($stats['game-stats'] as $key => $value) {
                $matches = [];

                if (preg_match('/runs_inning_(\d+)/', $key, $matches)) {
                    $number          = intval($matches[1]);
                    $inningCount     = max($inningCount, $number);
                    $result[$number] = $value;
                }
            }
        }

        return $result;
    }
}
