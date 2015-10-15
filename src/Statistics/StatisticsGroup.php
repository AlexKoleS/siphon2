<?php

namespace Icecave\Siphon\Statistics;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * Stores a single statistics group (based on the  stat-group XML element).
 */
class StatisticsGroup implements
    IteratorAggregate,
    Countable
{
    public function __construct($key, array $scopes = [], array $statistics = [])
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                'Group key must be a string.'
            );
        }

        foreach ($scopes as $type => $value) {
            if (!is_string($type)) {
                throw new InvalidArgumentException(
                    'Scope type must be a string.'
                );
            } elseif (!is_string($value)) {
                throw new InvalidArgumentException(
                    'Scope value must be a string.'
                );
            }
        }

        foreach ($statistics as $type => $value) {
            if (!is_string($type)) {
                throw new InvalidArgumentException(
                    'Statistic type must be a string.'
                );
            } elseif (!is_integer($value) && !is_float($value)) {
                throw new InvalidArgumentException(
                    'Statistic value must be an integer or float.'
                );
            }
        }

        $this->key        = $key;
        $this->scopes     = $scopes;
        $this->statistics = $statistics;
    }

    /**
     * Get the group key.
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the scopes for this stat group.
     *
     * @return array<string, string> A map of scope type to value.
     */
    public function scopes()
    {
        return $this->scopes;
    }

    /**
     * Get a single scope by its type.
     *
     * @return string $type The scope type.
     *
     * @return string|null The scope value, or null if no such scope exists.
     */
    public function scopeByType($type)
    {
        if (isset($this->scopes[$type])) {
            return $this->scopes[$type];
        }

        return null;
    }

    /**
     * Get an array of all statistics in the group.
     *
     * @param array<string>|null $types   A set of statistic types that the results are limited (or expanded) to.
     * @param mixed              $default The default value to use for expanded statistics.
     *
     * @return array<string, integer|float> A map of statistic type to value.
     */
    public function statistics(array $types = null, $default = 0)
    {
        if (null === $types) {
            return $this->statistics;
        }

        $result = [];

        foreach ($types as $type) {
            $result[$type] = $this->statisticByType($type, $default);
        }

        return $result;
    }

    /**
     * Get the value of a single statistic by its type.
     *
     * @param string $type    The statistic type.
     * @param mixed  $default The default value to return.
     *
     * @return mixed The statistic value, or $default if no such statistic exists.
     */
    public function statisticByType($type, $default = 0)
    {
        if (array_key_exists($type, $this->statistics)) {
            return $this->statistics[$type];
        }

        return $default;
    }

    /**
     * Check if the group contains statistics.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->statistics);
    }

    /**
     * Get the number of statistics in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->statistics);
    }

    /**
     * Iterate the statistics.
     *
     * @return mixed<string, integer|float> A sequence of statistics.
     */
    public function getIterator()
    {
        foreach ($this->statistics as $type => $value) {
            yield $type                     => $value;
        }
    }

    private $key;
    private $scopes;
    private $statistics;
}
