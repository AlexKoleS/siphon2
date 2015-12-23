<?php

namespace Icecave\Siphon\Reader;

/**
 * A trait used by all responses.
 */
trait ResponseTrait
{
    /**
     * Set the requested URL.
     *
     * @param string $url The requested URL.
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get the requested URL.
     *
     * Credentials will not appear in this value.
     *
     * @return string|null The requested URL, or null if unknown.
     */
    public function url()
    {
        return $this->url;
    }

    private $url;
}
