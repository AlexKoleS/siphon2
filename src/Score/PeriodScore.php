<?php
namespace Icecave\Siphon\Score;

/**
 * Scores for period based sports.
 */
class PeriodScore implements ScoreInterface
{
    use ScoreTrait;

    /**
     * Get the class name of the scope type used by this sport.
     *
     * @return string
     */
    public function scopeClass()
    {
        return Period::class;
    }
}
