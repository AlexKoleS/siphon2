<?php
namespace Icecave\Siphon\Score;

/**
 * A scope used for period-based sports (anything that does not use innings);
 *
 * @api
 */
interface PeriodInterface extends ScopeInterface
{
    /**
     * Get the type of the period.
     *
     * @return PeriodType The period type.
     */
    public function type();
}
