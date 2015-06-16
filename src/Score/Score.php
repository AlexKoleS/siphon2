<?php
namespace Icecave\Siphon\Score;

use Countable;
use IteratorAggregate;

class Score implements Countable, IteratorAggregate
{
    public function __construct(array $periods = [])
    {
        $this->periods = $periods;
    }

    /**
     * Get the competition score.
     *
     * @return tuple<integer, integer> A 2-tuple of score for the home and away teams.
     */
    public function score()
    {
        if (null === $this->score) {
            $home = 0;
            $away = 0;

            foreach ($this->periods as $period) {
                $home += $period->homeScore();
                $away += $period->awayScore();
            }

            $this->score = [$home, $away];
        }

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
