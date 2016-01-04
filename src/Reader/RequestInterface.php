<?php

namespace Icecave\Siphon\Reader;

use Serializable;

/**
 * Interface for requests.
 */
interface RequestInterface extends Serializable
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor);

    /**
     * Fetch the request's "rate-limit group".
     *
     * If the request is rate-limited, any other requests that are in the same
     * rate-limit group are also rate limited.
     *
     * @return string The rate-limit group.
     */
    public function rateLimitGroup();

    /**
     * @return string
     */
    public function __toString();
}
