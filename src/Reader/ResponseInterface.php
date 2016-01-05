<?php

namespace Icecave\Siphon\Reader;

use Icecave\Chrono\DateTime;

/**
 * Interface for responses.
 */
interface ResponseInterface
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor);

    /**
     * Get the time at which the data was last modified, if known.
     *
     * @return DateTime|null
     */
    public function modifiedTime();
}
