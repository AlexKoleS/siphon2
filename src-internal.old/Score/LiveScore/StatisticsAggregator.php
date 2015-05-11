<?php
namespace Icecave\Siphon\Score\LiveScore;

use Generator;
use SimpleXMLElement;

/**
 * Aggregates 'stat' XML elements per scope.
 */
class StatisticsAggregator
{
    /**
     * Extract values from 'stat' elements into an easily usable structures.
     *
     * The result value is an array of objects, each object represents a single
     * scope and has the following attributes:
     *
     * - type (period, overtime, shootout, inning, etc)
     * - number (the scope number from the 'num' attribute)
     * - home (an associated array of statistics for the home team)
     * - away (an associated array of statistics for the home team)
     *
     * @param SimpleXMLElement The XML document.
     *
     * @return array<stdClass>
     */
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

    /**
     * Normalize all home/away statistics arrays so that they are guaranteed to
     * contain all possible keys.
     *
     * @param array<stdClass>      &$result  The un-normalized result.
     * @param array<string, mixed> $defaults An associative array of defaults to add to each statistics array.
     */
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

    /**
     * Reduce a sequence of statistics 5-tuples from the map() method to
     * associative arrays inside the results array.
     *
     * @param array     &$result   The result object to populate.
     * @param array     &$defaults An array that is populated with default values that must be present in every scope.
     * @param string    $team      The string 'home' or 'away', depending on which team's stats are being populated.
     * @param Generator $stats     The result of the map() method.
     */
    private function reduce(
        array &$result,
        array &$defaults,
        $team,
        Generator $stats
    ) {
        foreach ($stats as list($scopeKey, $scopeType, $scopeNum, $key, $value)) {
            $defaults[$key] = 0;

            if (!isset($result[$scopeKey])) {
                $result[$scopeKey] = (object) [
                    'type'   => $scopeType,
                    'number' => $scopeNum,
                    'home'   => [],
                    'away'   => [],
                ]; // @codeCoverageIgnore
            }

            if (isset($result[$scopeKey]->{$team}[$key])) {
                $result[$scopeKey]->{$team}[$key] += $value;
            } else {
                $result[$scopeKey]->{$team}[$key] = $value;
            }
        }
    }

    /**
     * Yield a sequence of 5-tuples for each statistics present in the XML.
     *
     * Each 5-tuple contains:
     *
     * - ordering (an integer used to correctly sort the scopes)
     * - scope type (period, overtime, shootout, inning, etc)
     * - scope number (the scope number from the 'num' attribute)
     * - stat key
     * - stat value
     *
     * @param array<SimpleXMLElement> $groups An array of 'stat-group' elements.
     *
     * @return mixed<tuple<integer, string, integer, string, integer>> A sequence of 5-tuples.
     */
    private function map($groups)
    {
        $ordering = [
            'inning'       => 1 << 8,
            'extra-inning' => 2 << 8,
            'period'       => 1 << 8,
            'overtime'     => 2 << 8,
            'shootout'     => 3 << 8,
        ];

        foreach ($groups as $group) {
            $scopeType = strval($group->scope['type']);
            $scopeNum  = intval($group->scope['num']);

            if ('competition' !== $scopeType) {
                foreach ($group->stat as $stats) {
                    yield [
                        $ordering[$scopeType] | $scopeNum,
                        $scopeType,
                        $scopeNum,
                        strval($stats['type']),
                        intval($stats['num']),
                    ]; // @codeCoverageIgnore
                }
            }
        }
    }
}
