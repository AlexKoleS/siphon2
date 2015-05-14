<?php
namespace Icecave\Siphon\Reader;

/**
 * Interface for requests.
 */
interface RequestInterface
{
    /**
     * Dispatch a call to the given visitor.
     *
     * @param RequestVisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(RequestVisitorInterface $visitor);
}
