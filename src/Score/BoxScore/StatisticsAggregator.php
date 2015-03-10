<?php
namespace Icecave\Siphon\Score\BoxScore;

use Generator;
use SimpleXMLElement;

class StatisticsAggregator implements StatisticsAggregatorInterface
{
    public function extract(SimpleXMLElement $xml)
    {
        $result   = [];
        $defaults = [];

        $this->reduce(
            $result,
            $defaults,
            'home',
            $this->map(
                $xml->xpath('//home-team-content/stat-group')
            )
        );

        $this->reduce(
            $result,
            $defaults,
            'away',
            $this->map(
                $xml->xpath('//away-team-content/stat-group')
            )
        );

        $this->normalize(
            $result,
            $defaults
        );

        return $result;
    }

    private function normalize(
        array &$result,
        array $defaults
    ) {
        ksort($result);

        foreach ($result as $scope) {
            $scope->home += $defaults;
            $scope->away += $defaults;

            ksort($scope->home);
            ksort($scope->away);
        }

        $result = array_values($result);
    }

    private function reduce(
        array &$result,
        array &$defaults,
        $team,
        Generator $stats
    ) {
        foreach ($stats as list($scope, $type, $key, $value)) {
            $defaults[$key] = 0;

            if (!isset($result[$scope])) {
                $result[$scope] = (object) [
                    'type' => $type,
                    'home' => [],
                    'away' => [],
                ]; // @codeCoverageIgnore
            }

            if (isset($result[$scope]->{$team}[$key])) {
                $result[$scope]->{$team}[$key] += $value;
            } else {
                $result[$scope]->{$team}[$key] = $value;
            }
        }
    }

    private function map($groups)
    {
        $ordering = [
            'inning'   => 1 << 8,
            'period'   => 1 << 8,
            'overtime' => 2 << 8,
            'shootout' => 3 << 8,
        ];

        $prefixes = [];
        $stats    = null;

        foreach ($groups as $group) {
            // Scan the "results" statistics to work out which game stats we're
            // interested in (ie, points, hits, runs, errors, etc) ...
            if ('results' === strval($group->key)) {
                foreach ($group->stat as $stat) {
                    $prefixes[] = strval($stat['type']);
                }

            // Store the stats for later consumption ...
            } elseif ('game-stats' === strval($group->key)) {
                $stats = $group;
            }
        }

        $pattern = sprintf(
            '/(%s)_(%s)(?:_(\d+))?/',
            implode('|', array_map('preg_quote', $prefixes)),
            implode('|', array_map('preg_quote', array_keys($ordering)),


        );

        // $typePatterns = [];

        // foreach ($resultGroup->stat as $stat) {
        //     foreach ($ordering as $prefix => $order) {
        //         $typePatterns[] = '/^' . preg_quote($stat['type'] . '_' . $prefix, '/') . '$/'
        //         $typePatterns[] = '/^' . preg_quote($stat['type'] . '_' . $prefix, '/') . '$/'
        //     }
        //     $prefixes[] = strval($stat['type']);
        // }

        // foreach ($statsGroup->stat as $stat) {
        //     if (preg_match('/^$/', $stat))
        // }


        // foreach ($groups as $group) {
        //     if ('game-stats' !== strval($group->key)) {
        //         continue;
        //     }

        //     foreach ($groups->stat as $stat) {

        //     }

        //     $scopeType = strval($group->scope['type']);
        //     $scopeNum  = intval($group->scope['num']);

        //     if ('competition' !== $scopeType) {
        //         foreach ($group->stat as $stats) {
        //             yield [
        //                 $ordering[$scopeType] | $scopeNum,
        //                 $scopeType,
        //                 strval($stats['type']),
        //                 intval($stats['num']),
        //             ]; // @codeCoverageIgnore
        //         }
        //     }
        // }
    }
}
