<?php

namespace Icecave\Siphon\Reader;

/**
 * Interface for responses.
 */
interface ResponseInterface
{
    /**
     * Get the requested URL.
     *
     * Credentials will not appear in this value.
     *
     * @return string The requested URL.
     */
    public function url();

    /**
     * Dispatch a call to the given visitor.
     *
     * @param ResponseVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(ResponseVisitorInterface $visitor);
}
