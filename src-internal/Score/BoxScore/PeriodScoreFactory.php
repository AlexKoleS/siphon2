<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\Period;
use Icecave\Siphon\Score\PeriodScore;
use Icecave\Siphon\Score\PeriodType;
use Icecave\Siphon\Util;
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
        return isset($this->periodConfiguration[$sport . '/' . $league]);
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

        $sportKey            = $sport . '/' . $league;
        $periodConfiguration = $this->periodConfiguration[$sportKey];
        $periodCounts        = [];

        foreach ($periodConfiguration as $type => $count) {
            $periodCounts[$type] = 0;
        }

        $homeTeamScores = $this->extractScores(
            $homeTeamStats,
            $periodCounts,
            $sportKey
        );

        $awayTeamScores = $this->extractScores(
            $awayTeamStats,
            $periodCounts,
            $sportKey
        );

        $indexedConfiguration = [];
        foreach ($periodConfiguration as $type => $count) {
            $indexedConfiguration[] = [$type, $count];
        }

        for ($index = 1; $index < count($indexedConfiguration); ++$index) {
            list($type, $minCount) = $indexedConfiguration[$index];
            $actualCount           = $periodCounts[$type];

            if (!$actualCount && $index > 1) {
                continue;
            }

            list($prevType, $prevCount) = $indexedConfiguration[$index - 1];
            $periodCounts[$prevType]    = max($periodCounts[$prevType], $prevCount);
        }

        $result = new PeriodScore;

        foreach ($periodCounts as $type => $count) {
            for ($index = 1; $index <= $count; ++$index) {
                $result->add(
                    new Period(
                        $this->periodType($type),
                        isset($homeTeamScores[$type][$index]) ? $homeTeamScores[$type][$index] : 0,
                        isset($awayTeamScores[$type][$index]) ? $awayTeamScores[$type][$index] : 0
                    )
                );
            }
        }

        return $result;
    }

    private function extractScores(array $stats, array &$periodCounts, $sportKey)
    {
        $pattern = sprintf(
            '/%s_(%s)(?:_(\d+))?/',
            preg_quote($this->scoreKey[$sportKey], '/'),
            implode(
                '|',
                array_map(
                    function ($type) {
                        return preg_quote($type, '/');
                    },
                    array_keys($this->periodConfiguration[$sportKey])
                )
            )
        );

        $result = [];

        if (isset($stats['game-stats'])) {
            foreach ($stats['game-stats'] as $key => $value) {
                $matches = [];

                if (preg_match($pattern, $key, $matches)) {
                    $type = $matches[1];

                    if (isset($matches[2])) {
                        $number = intval($matches[2]);
                    } else {
                        $number = 1;
                    }

                    $periodCounts[$type] = max($periodCounts[$type], $number);

                    if (!isset($result[$type])) {
                        $result[$type] = [];
                    }

                    $result[$type][$number] = $value;
                }
            }
        }

        return $result;
    }

    private function periodType($key)
    {
        if ('overtime' === $key) {
            return PeriodType::OVERTIME();
        } elseif ('shootout' === $key) {
            return PeriodType::SHOOTOUT();
        }

        return PeriodType::PERIOD();
    }

    private $scoreKey = [
        'football/NFL'     => 'points',
        'football/NCAAF'   => 'points',
        'basketball/NBA'   => 'points',
        'basketball/NCAAB' => 'points',
        'hockey/NHL'       => 'goals',
    ];

    /**
     * This is a map of sport/league to the support period types.
     *
     * The period type maps to the minimum number of periods of that type that
     * must exist before the next period type may be used.
     */
    private $periodConfiguration = [
        'football/NFL' => [
            'quarter'  => 4,
            'overtime' => 1,
        ],
        'football/NCAAF' => [
            'quarter'  => 4,
            'overtime' => 1,
        ],
        'basketball/NBA' => [
            'quarter'  => 4,
            'overtime' => 1,
        ],
        'basketball/NCAAB' => [
            'half'     => 2,
            'overtime' => 1,
        ],
        'hockey/NHL' => [
            'period'   => 3,
            'overtime' => 1,
            'shootout' => 3,
        ],
    ];
}
