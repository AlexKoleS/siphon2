<?php

namespace Icecave\Siphon\Score;

use Countable;
use IteratorAggregate;

class Score implements Countable, IteratorAggregate
{
    public function __construct(
        $homeTeamScore = 0,
        $awayTeamScore = 0,
        array $periods = []
    ) {
        $this->score = [$homeTeamScore, $awayTeamScore];
        $this->periods = $periods;
    }

    /**
     * Get the competition score.
     *
     * @return tuple<integer, integer> A 2-tuple of score for the home and away teams.
     */
    public function score()
    {
        return $this->score;
    }

    /**
     * Get the number of scopes in the score.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->periods);
    }

    public function getIterator()
    {
        foreach ($this->periods as $period) {
            yield $period;
        }
    }

    private $score;
    private $periods;
}
