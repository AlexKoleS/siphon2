<?php
namespace Icecave\Siphon\Atom;

/**
 * Construct RequestInterface objects from the URLs returned by the Atom feed.
 */
interface RequestFactoryInterface
{
    /**
     * Create a request from a URL.
     *
     * @param string $url The URL.
     *
     * @return RequestInterface         A request object representing a request to the given URL.
     * @throws InvalidArgumentException If the URL can not be mapped to an appropriate request object.
     */
    public function create($url);
}
