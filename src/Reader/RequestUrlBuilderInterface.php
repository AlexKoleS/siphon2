<?php

namespace Icecave\Siphon\Reader;

/**
 * The interface implemented by request URL builders.
 */
interface RequestUrlBuilderInterface
{
    /**
     * Build a feed URL from a request object.
     *
     * @param RequestInterface $request The request.
     *
     * @return string The fully-qualified feed URL.
     */
    public function build(RequestInterface $request);
}
