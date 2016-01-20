<?php

namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Sport;

/**
 * Interface for response that are specific to a particular sport.
 */
interface SportResponseInterface extends ResponseInterface
{
    /**
     * Get the sport.
     *
     * @return Sport The sport.
     */
    public function sport();
}
