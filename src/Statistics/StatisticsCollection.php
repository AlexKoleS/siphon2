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
     * Iterate the statistics.
     *
     * @return mixed<string, integer|float> A sequence of statistics.
     */
    public function getIterator()
    {
        foreach ($this->groups as $key => $group) {
            yield $key                 => $group;
        }
    }

    private $groups;
}
