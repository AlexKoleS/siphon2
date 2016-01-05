<?php

namespace Icecave\Siphon\Reader;

use Icecave\Chrono\DateTime;

/**
 * A trait with common functionality for all responses.
 */
trait ResponseTrait
{
    /**
     * Get the time at which the data was last modified, if known.
     *
     * @return DateTime|null
     */
    public function modifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Set the time at which the data was last modified, if known.
     *
     * @param DateTime|null $modifiedTime
     */
    public function setModifiedTime(DateTime $modifiedTime = null)
    {
        $this->modifiedTime = $modifiedTime;
    }

    private $modifiedTime;
}
