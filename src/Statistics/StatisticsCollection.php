<?php

namespace Icecave\Siphon\Statistics;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * A collection of statistic groups.
 */
class StatisticsCollection implements
    IteratorAggregate,
    Countable
{
    public function __construct(array $groups = [])
    {
        $this->groups = [];

        foreach ($groups as $group) {
            if (!$group instanceof StatisticsGroup) {
                throw new InvalidArgumentException(
                    'Expected array of StatisticsGroup instances.'
                );
            }

            $this->groups[$group->key()] = $group;
        }
    }

    /**
     * Check if the group contains statistics.
     *
     * @param boolean True if the response is empty; otherwise, false.
     */
    public function isEmpty()
    {
        return empty($this->groups);
    }

    /**
     * Get the number of statistics in the response.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->groups);
    }

    /**
     * Fetch a statistics group by key, if it exists.
     *
     * @param string $key The group key.
     *
     * @return StatisticsGroup|null The statistics group with the given key, or null if the group is not present.
     */
    public function findGroupByKey($key)
    {
        if (isset($this->groups[$key])) {
            return $this->groups[$key];
        }

        return null;
    }

    /**
     * Iterate the statistics.
     *
     * @return mixed<string, StatisticsGroup> A sequence of statistics groups.
     */
    public function getIterator()
    {
        foreach ($this->groups as $key => $group) {
            yield $key                 => $group;
        }
    }

    private $groups;
}
