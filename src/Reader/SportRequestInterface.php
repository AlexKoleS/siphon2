<?php

namespace Icecave\Siphon\Reader;

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
