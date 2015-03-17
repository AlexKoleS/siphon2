<?php
namespace Icecave\Siphon\Score\LiveScore;

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

        foreach ($groups as $group) {
            $scopeType = strval($group->scope['type']);
            $scopeNum  = intval($group->scope['num']);

            if ('competition' !== $scopeType) {
                foreach ($group->stat as $stats) {
                    yield [
                        $ordering[$scopeType] | $scopeNum,
                        $scopeType,
                        strval($stats['type']),
                        intval($stats['num']),
                    ]; // @codeCoverageIgnore
                }
            }
        }
    }
}
