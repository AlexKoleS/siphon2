<?php
namespace Icecave\Siphon\Player;

use IteratorAggregate;

/**
 * Player statistics.
 *
 * @api
 */
interface StatisticsInterface extends IteratorAggregate
{
    /**
     * Get the player ID.
     *
     * @return string The player ID.
     */
    public function playerId();

    /**
     * Get the season name.
     *
     * @return string The season name.
     */
    public function season();

    /**
     * Get the value for specific statistical metric.
     *
     * @param string $group   The group, (eg: "regular-season-stats", "post-season-stats").
     * @param string $key     The particular statistic to fetch (eg: "games_played").
     * @param mixed  $default The value to return if the key is not present.
     *
     * @return integer|float
     */
    public function get($group, $key, $default = 0);

    /**
     * Get the total value for a specific statistical metric across all groups.
     *
     * @param string $key The particular statistic to fetch (eg: "games_played").
     *
     * @return integer|float
     */
    public function getTotal($key);

    /**
     * Check if a given key exists within a group.
     *
     * @param string $group The group, (eg: "regular-season-stats", "post-season-stats").
     * @param string $key   The particular statistic to check (eg: "games_played").
     */
    public function has($group, $key);

    /**
     * Iterate over statistical totals.
     *
     * @return mixed<string, integer|float> A map of key name to total value.
     */
    public function getIterator();

    /**
     * Iterate over a flat sequence of groups and keys.
     *
     * @return mixed<tuple<string, string, integer|float>> A sequence of 3-tuples containing group name, key name and value.
     */
    public function iterate();

    /**
     * Iterate over the the statistics by group.
     *
     * The key is the group name, the value is an associative array mapping
     * statistical key to value.
     *
     * @return mixed<string, array<string, integer|float>>
     */
    public function iterateByGroup();

    /**
     * Iterate over the the statistics by key.
     *
     * The key is the statistical key, the value is an associative array mapping
     * group name to value.
     *
     * @return mixed<string, array<string, integer|float>>
     */
    public function iterateByKey();
}
