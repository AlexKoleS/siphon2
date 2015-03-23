<?php
namespace Icecave\Siphon\Player;

use ArrayIterator;

/**
 * Player statistics.
 */
class Statistics implements StatisticsInterface
{
    /**
     * @param string                                      $playerId
     * @param string                                      $season
     * @param array<string, array<string, integer|float>> $groups
     */
    public function __construct($playerId, $season, array $groups = [])
    {
        $this->playerId = $playerId;
        $this->season   = $season;
        $this->groups   = $groups;
    }

    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId()
    {
        return $this->playerId;
    }

    /**
     * Get the season name.
     *
     * @return string The season name.
     */
    public function season()
    {
        return $this->season;
    }

    /**
     * Get the value for specific statistical metric.
     *
     * @param string $group   The group, (eg: "regular-season-stats", "post-season-stats").
     * @param string $key     The particular statistic to fetch (eg: "games_played").
     * @param mixed  $default The value to return if the key is not present.
     *
     * @return integer|float
     */
    public function get($group, $key, $default = 0)
    {
        if (isset($this->groups[$group][$key])) {
            return $this->groups[$group][$key];
        }

        return $default;
    }

    /**
     * Get the total value for a specific statistical metric across all groups.
     *
     * @param string $key The particular statistic to fetch (eg: "games_played").
     *
     * @return integer|float
     */
    public function getTotal($key)
    {
        $total = 0;

        foreach ($this->groups as $group => $stats) {
            if (isset($stats[$key])) {
                $total += $stats[$key];
            }
        }

        return $total;
    }

    /**
     * Check if a given key exists within a group.
     *
     * @param string $group The group, (eg: "regular-season-stats", "post-season-stats").
     * @param string $key   The particular statistic to check (eg: "games_played").
     */
    public function has($group, $key)
    {
        return isset($this->groups[$group][$key]);
    }

    /**
     * Iterate over statistical totals.
     *
     * @return mixed<string, integer|float> A map of key name to total value.
     */
    public function getIterator()
    {
        $result = [];

        foreach ($this->groups as $group => $stats) {
            foreach ($stats as $key => $value) {
                if (isset($result[$key])) {
                    $result[$key] += $value;
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return new ArrayIterator($result);
    }

    /**
     * Iterate over a flat sequence of groups and keys.
     *
     * @return mixed<tuple<string, string, integer|float>> A sequence of 3-tuples containing group name, key name and value.
     */
    public function iterate()
    {
        foreach ($this->groups as $group => $stats) {
            foreach ($stats as $key => $value) {
                yield [$group, $key, $value];
            }
        }
    }

    /**
     * Iterate over the the statistics by group.
     *
     * The key is the group name, the value is an associative array mapping
     * statistical key to value.
     *
     * @return mixed<string, array<string, integer|float>>
     */
    public function iterateByGroup()
    {
        $defaults = [];

        foreach ($this->groups as $group => $stats) {
            foreach ($stats as $key => $value) {
                $defaults[$key] = 0;
            }
        }

        foreach ($this->groups as $group => $stats) {
            yield $group                 => array_merge($defaults, $stats);
        }
    }

    /**
     * Iterate over the the statistics by key.
     *
     * The key is the statistical key, the value is an associative array mapping
     * group name to value.
     *
     * @return mixed<string, array<string, integer|float>>
     */
    public function iterateByKey()
    {
        $defaults = [];
        $result   = [];

        foreach ($this->groups as $group => $stats) {
            $defaults[$group] = 0;
        }

        foreach ($this->groups as $group => $stats) {
            foreach ($stats as $key => $value) {
                if (!isset($result[$key])) {
                    $result[$key] = $defaults;
                }

                $result[$key][$group] = $value;
            }
        }

        return new ArrayIterator($result);
    }

    private $playerId;
    private $season;
    private $groups;
}
