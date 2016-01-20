<?php

namespace Icecave\Siphon\Reader;

use Icecave\Siphon\Sport;

/**
 * Interface for requests that are specific to a particular sport.
 */
interface SportRequestInterface extends RequestInterface
{
    /**
     * Get the requested sport.
     *
     * @return Sport The requested sport.
     */
    public function sport();
}
